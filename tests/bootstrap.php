<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/psr-container.php';

$builder = new \DI\ContainerBuilder();

foreach (glob(__DIR__ . '/config/*.conf.php') as $subconfig) {
  $builder->addDefinitions(require $subconfig);
}

$container = $builder->build();
