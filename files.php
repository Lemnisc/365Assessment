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
    $selectedFile = $app['database']->getWhereKeyIsValue('files', 'file_id', $_GET['file']);
}
// TODO check file ownership

// If a file is posted, upload it to the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get id of uploader
    $user_id = $_SESSION['id'];
    // Get filename of uploaded file
    // Note: can upload different files with the same filename
    $file_name =  $_FILES['file-upload']['name'];

    $file = fopen($_FILES['file-upload']['tmp_name'], 'r');

    $headers = explode(";", fgetcsv($file)[0]);

    while (!feof($file)) {
        // Insert line into database
        // print_r(fgetcsv($file));
        $data = explode(";", fgetcsv($file)[0]);
        $data = $data . ',null';

        $headers = ['foo', 'bar', 'bax'];
        $data = [1,2,3];
        print_r($headers);
        print_r($data);
        $line = ['user_id' => $user_id, 'file_id'=>3, 'filename'=>$file_name, $data];
        $line = implode(',',$line);
        $combined = array_combine($headers, $data);
        die(print_r($combined));
        // do header: data, header:data
        $app['database']->insert('files', $line);
    }

    fclose($file);
}

require 'views/files.view.php';
