<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/psr-container.php';

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions((new \Mqtt\Bootstrap())->getDiDefinitions());

foreach (glob(__DIR__ . '/config/*.conf.php') as $subconfig) {
  $builder->addDefinitions(require $subconfig);
}

$container = $builder->build();
