<?php

return [
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=mysql;dbname=pik-rent',
        'username' => 'root',
        'password' => '12345678',
        'charset' => 'utf8',
    ],
    'dbMigration' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost:3307;dbname=pik-rent',
        'username' => 'root',
        'password' => '12345678',
        'charset' => 'utf8',
    ]
];
