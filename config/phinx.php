<?php
/**
 * Copyright SuntechSoft (c) 2017-2018.
 */

/** @var ContainerInterface $container */

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

$container = require __DIR__  . '/container.php';

/** @var AdapterInterface $adapter */
$adapter = $container->get(AdapterInterface::class);

return [
    'paths' => [
        'migrations' => __DIR__ . '/../migrations',
    ],
    'environments' => [
        "default_database" => "dev",
        'dev' => [
            'connection' => $adapter->getDriver()->getConnection()->getResource(),
            'name' => $adapter->getDriver()->getConnection()->getCurrentSchema()
        ],
        'prod' => [

        ]
    ]
];