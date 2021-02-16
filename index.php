<?php

$database = require 'bootstrap.php';
$users = $database->selectAll('users');
require 'views/index.view.php';
