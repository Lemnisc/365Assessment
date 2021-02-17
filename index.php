<?php

require 'bootstrap.php';
// die(var_dump($app));
$users = $app['database']->selectAll('users');
require 'views/index.view.php';

session_start();
if (
    !isset($_SESSION["loggedin"])
    || !$_SESSION["loggedin"]
) {
    header("location: /auth/login.php");
    exit;
}
