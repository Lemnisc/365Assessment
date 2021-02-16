<?php

require 'bootstrap.php';
// die(var_dump($app));
$users = $app['database']->selectAll('users');
require 'views/index.view.php';
