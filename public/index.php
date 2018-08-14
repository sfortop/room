<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */


/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */
require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../c3.php')) {
include __DIR__ . '/../c3.php';
}

call_user_func(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    chdir(dirname(__DIR__));

    $app = new \App\App($container);

    $runner = new Zend\HttpHandlerRunner\Emitter\SapiEmitter();

    $runner->emit($app->run());
});
