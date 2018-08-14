#!/usr/bin/php
<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

require __DIR__ . '/../vendor/autoload.php'; // composer autoloader

// better to use common config to have single point of entrance
// but for simplicity use concrete

$config = require __DIR__ . '/../config/hydrator.global.php';

if (!isset($config['hydrator'])) {
    throw new \Infrastructure\InvalidConfigException("section 'hydrators' missed");
}
if (!isset($config['hydrator']['path'])) {
    throw new \Infrastructure\InvalidConfigException("section 'hydrators.path' missed");
}


$classes = $config['hydrator']['classes'] ?? [];
$path = $config['hydrator']['path'];

// map
$hydratorFQCNs = [];
foreach ($classes as $class) {
    $config = new \GeneratedHydrator\Configuration($class);
    $config->setGeneratedClassesTargetDir($path);
    $hydratorFQCNs[$class] = $config->createFactory()->getHydratorClass();
}

$configName = dirname(realpath($path)) . '/config/hydrator.global.php';


file_put_contents($configName, '<?' . 'php' . PHP_EOL . 'return ' . var_export(['hydrator' => ['classmap' => $hydratorFQCNs]], true) . ';');