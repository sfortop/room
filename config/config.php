<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$aggregator = new ConfigAggregator([
    \Zend\Di\ConfigProvider::class,
    \Zend\Hydrator\ConfigProvider::class,
    \Zend\Db\ConfigProvider::class,
    new PhpFileProvider(realpath(__DIR__) . '/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider(realpath(__DIR__) . '/../generated/config/{{,*.}global,{,*.}local}.php'),
]);

return $aggregator->getMergedConfig();
