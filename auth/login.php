<?php
$config = require __DIR__ . '\..\config.php';
$site_root = $config['site_root'];

$database = require $site_root . '\bootstrap.php';
$errors = [];
$oldInput = [];


session_start();

if (
    isset($_SESSION["loggedin"])
    && $_SESSION["loggedin"]
) {
    header("location: /index.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST['email']))) {
        $errors['email'] = "Enter an email address.";
    } else if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email adress";
        $oldInput['email'] = trim($_POST['email']);
    }
    if (empty(trim($_POST['password']))) {
        $errors['password'] = "Enter a password.";
    }
    
    
    
    if (!count($errors)) {
        $foo = $app['database']->getWhereKeyIsValue('users', 'email', trim($_POST['email']));

        var_dump($foo[0]);
        if(password_verify($_POST['password'], $foo[0]->password)){
            session_start();
            $_SESSION['loggedin']=true;
            $_SESSION['id']=$foo[0]->id;
            $_SESSION['email']=$foo[0]->email;
            header("location: /index.php");
        }
        //TODO else could not verify password
    }
    // TODO else show errors
    
}



require  $site_root . '\.\views\login.view.php';
