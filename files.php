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

class File
{
    private $app;
    public $selectedFile;
    public function __construct($app)
    {
        $this->app = $app;
    }
    public function getFileList()
    {
        // Get the list of files, 
        $files = $this->app['database']->selectColumns('files', ['file_id', 'filename']);
        // and remove duplicate entries
        $files = array_unique($files, SORT_REGULAR);
        return $files;
    }
    public function handleRequest()
    {
        if (isset($_GET['download'])) {
            $this->doDownload($_GET['download']);
        }

        if (isset($_GET['delete'])) {
            $this->doDelete($_GET['delete']);
        }

        if (isset($_GET['file'])) {
            $this->getFile($_GET['file']);
        }
    }

    public function handlePostRequest()
    {
        if (isset($_FILES['file-upload'])) {
            $this->doFileUpload();
            header('Location: ' . '/files.php');
            die();
        }

        if (isset($_REQUEST['download'])) {
            $this->doDownload($_REQUEST['download']);
        }
        if (isset($_REQUEST['delete'])) {
            $this->doDelete($_REQUEST['delete']);
        }
        if (isset($_REQUEST['edit'])) {
            $this->getFile($_REQUEST['edit']);
        }
        if (isset($_REQUEST['update'])) {
            $this->doUpdate($_REQUEST['update']);
        }
    }
    public function doUpdate($rows)
    {
        $data = json_decode($rows, TRUE);
        foreach ($data as $row) {
            $id_column = 'id';
            $id_value = $row['id'];
            $table = 'files';
            $parameters['boekjaar'] = $row['boekjaar'];
            $parameters['week'] = $row['week'];
            $parameters['datum'] = $row['datum'];
            $parameters['persnr'] = $row['persnr'];
            $parameters['uren'] = str_replace(',', '.', $row['uren']);
            $parameters['uurcode'] = '"'.$row['uurcode'].'"';
            $this->app['database']->updateRowWhereColumnIsValue($table, $parameters, $id_column, $id_value);
        }
        die();
    }
    public function doDownload($id)
    {
        $columns = [
            'Boekjaar', 'Week', 'Datum', 'Persnr', 'Uren', 'Uurcode',
        ];
        $data = $this->app['database']->getColumnsWhereKeyIsValue('files', 'file_id', $id, $columns);
        $filename = $this->app['database']->getColumnsWhereKeyIsValue('files', 'file_id', $id, ['filename'])[0]->filename;

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename);
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns, ';');

        foreach ($data as $row => $value) {
            $row = (array)$value;
            // Format the date as it was
            $row['Datum'] = date('d/m/Y', strtotime($row['Datum']));
            // Format the hours as it was
            $row['Uren'] = str_replace('.', ',', $row['Uren']);
            fputcsv($file, (array)$row, ';');
        }
        fclose($file);
        exit;
    }

    public function doDelete($id)
    {
        $this->app['database']->deleteByColumnValue('files', 'file_id', $id);
        header('Location: ' . '/files.php');
        die();
    }

    public function doFileUpload()
    {
        // Get id of uploader
        $user_id = $_SESSION['id'];
        // Get filename of uploaded file
        // Note: can upload different files with the same filename
        $filename =  $_FILES['file-upload']['name'];

        $file = fopen($_FILES['file-upload']['tmp_name'], 'r');

        $headers = explode(";", fgetcsv($file, ';')[0]);

        // Find the highest file_id in the table, to increment for the new file
        // NOTE: This will give issues when users upload files at the same time, but using a framework properly would help. Or uploading the files as blobs, instead of as data in the database
        // die(print_r($app['database']->getMaxFromColumn('files', 'file_id')));
        $newIndex = 1 + $this->app['database']->getMaxFromColumn('files', 'file_id');
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
            // Replace the comma in the hours number with a period, to adhere to the standard
            $combined['Uren'] = str_replace(',', '.', $combined['Uren']);
            // Finally, insert it into the database
            $this->app['database']->insert('files', $combined);
        }

        fclose($file);
    }

    public function getFile($id)
    {
        $this->selectedFile = $this->app['database']->getWhereKeyIsValue('files', 'file_id', $id);
    }
}


$file = new File($app);
$files = $file->getFileList();

// If a file is posted, upload it to the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file->handlePostRequest();
}

if ($_REQUEST) {
    // TODO check file ownership
    $file->handleRequest();
}

require 'views/files.view.php';
