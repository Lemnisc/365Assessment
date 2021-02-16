<?php

return [
    'site_root' => __DIR__,
    'database' => [
        'name' => '365Assessment',
        'username' => 'root',
        'password' => '',
        'connection' => 'mysql:host=127.0.0.1',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ],
    ],
    'title' => '365 Assessment'
];
