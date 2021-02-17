<?php
$config = require_once __DIR__ . '\..\config.php';
$site_root = $config['site_root'];

$database = require $site_root . '\bootstrap.php';
$errors = [];
$oldInput = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST['email']))) {
        $errors['email'] = "Enter an email address.";
    }else if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email adress";
        $oldInput['email'] = trim($_POST['email']);
      }
    if (empty(trim($_POST['password']))) {
        $errors['password'] = "Enter a password.";
    }
    if (trim($_POST['password']) != trim($_POST['passwordConfirmation'])) {
        $errors['passwordConfirmation'] = "Passwords do not match.";
        $oldInput['password'] = trim($_POST['password']);
    }
    if (!count($errors)) {
        $app['database']->insert('users', [
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ]);
    }
}
require  $site_root . '\.\views\register.view.php';
