<?php

require 'bootstrap.php';

$config = require __DIR__ . '\config.php';

// If the user is not logged in, redirect them to login page
session_start();
if (
    !isset($_SESSION["loggedin"])
    || !$_SESSION["loggedin"]
) {
    header("location: /auth/login.php");
    exit;
}

// Get the list of files, 
$files = $app['database']->selectColumns('files', ['file_id', 'filename']);
// and remove duplicate entries
$files = array_unique($files, SORT_REGULAR);

// Get the file for displaying, if selected
if ($_REQUEST) {
    if (isset($_GET['download'])) {
        $columns = [
            'Boekjaar', 'Week', 'Datum', 'Persnr', 'Uren', 'Uurcode',
        ];
        $data = $app['database']->getColumnsWhereKeyIsValue('files', 'file_id', $_GET['download'], $columns);
        $filename = $app['database']->getColumnsWhereKeyIsValue('files', 'file_id', $_GET['download'], ['filename'])[0]->filename;

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename);
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns, ';');

        foreach ($data as $row => $value) {
            $row = (array)$value;
            $date = date('d/m/Y', strtotime($row['Datum']));
            $row['Datum'] = $date;
            fputcsv($file, (array)$row, ';');
        }
        fclose($file);
        exit;
    }

    if (isset($_GET['file'])) {
        $selectedFile = $app['database']->getWhereKeyIsValue('files', 'file_id', $_GET['file']);
    }
}

// TODO check file ownership

// If a file is posted, upload it to the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get id of uploader
    $user_id = $_SESSION['id'];
    // Get filename of uploaded file
    // Note: can upload different files with the same filename
    $filename =  $_FILES['file-upload']['name'];

    $file = fopen($_FILES['file-upload']['tmp_name'], 'r');

    $headers = explode(";", fgetcsv($file, ';')[0]);

    // Find the highest file_id in the table, to increment for the new file
    // NOTE: This will give issues when users upload files at the same time, but using a framework properly would help. Or uploading the files as blobs, instead of as data in the database
    $newIndex = 1 + $app['database']->getMaxFromColumn('files', 'file_id');
    // Insert each line into database
    // while (!feof($file)) { // Can't do it like this: It reads the end-of-file character too, so we have to stop right before, like so:
    while (($character = fgetc($file)) !== false) {
        // Read the line from the file
        $data = fgetcsv($file, 1000, ';');
        // Make sure to append the character we used to check for eof
        $data[0] = $character . $data[0];
        // Give it the proper headers
        $combined = array_combine($headers, $data);
        // Add the fields that aren't in the file:
        $combined['user_id'] = $user_id;
        $combined['filename'] = $filename;
        // Give it the proper file_id:
        $combined['file_id'] = $newIndex;
        // Parse and give it the date:
        $mysqlDate = date('Y-m-d', strtotime($data[2]));
        $combined['Datum'] = $mysqlDate;

        // Finally, insert it into the database
        $app['database']->insert('files', $combined);
    }

    fclose($file);
}

require 'views/files.view.php';
