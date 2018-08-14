<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */
$password = getenv('MYSQL_PASSWORD') ?: 'suTY44Rprwj5';
$user = getenv('MYSQL_USER') ?: 'root';
$database = getenv('MYSQL_DATABASE') ?: 'room';
$host = getenv('MYSQL_HOST') ?: 'db';
$port = getenv('MYSQL_PORT') ?: 3306;

return [
    'db' => [
        'driver'   => 'Pdo_Mysql',
        'host'     => $host,
        'dsn'      => sprintf('mysql:host=%s;port=%s;dbname=%s;', $host, $port, $database),
        'database' => $database,
        'user'     => $user,
        'password' => $password,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ),
    ],
    'dependencies' => [
        'factories' => [
        ],
    ],
];