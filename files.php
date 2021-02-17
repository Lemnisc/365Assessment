<?php

require 'bootstrap.php';

session_start();


if (
    !isset($_SESSION["loggedin"])
    || !$_SESSION["loggedin"]
) {
    header("location: /auth/login.php");
    exit;
}

$files = $app['database']->selectColumns('files', ['file_id', 'filename']);


$selectedFile = $app['database']->getWhereKeyIsValue('files', 'file_id', $_GET['file']);
// TODO check file ownership


require 'views/files.view.php';
