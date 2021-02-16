<?php
$config = require_once __DIR__ . '\..\config.php';
$site_root = $config['site_root'];

// $pdo = require $site_root . '\database\Connection.php';
$database = require $site_root . '\bootstrap.php';
require  $site_root . '\.\views\register.view.php';

// var_dump($app['database']);

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(empty(trim($_POST['email']))){
        $error_email = "Enter an email address.";
    } else {
        $app['database']->insert('users', [
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ]);
        
    }
}