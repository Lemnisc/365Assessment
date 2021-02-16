<?php
define('BASE_DIR', realpath(__FILE__));
$app = [];
$app['config'] = require 'config.php';
require 'database/Connection.php';
require 'database/QueryBuilder.php';

$app['database'] = new QueryBuilder(
    Connection::make($app['config']['database'])
);
