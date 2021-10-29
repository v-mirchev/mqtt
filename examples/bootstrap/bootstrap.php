<?php

require_once __DIR__ . '/../../vendor/autoload.php';

return (function () {

  $builder = new \DI\ContainerBuilder();
  $builder->addDefinitions((new \Mqtt\Bootstrap())->getDiDefinitions());

  foreach (glob(__DIR__ . '/config/*.conf.php') as $definition) {
    $builder->addDefinitions(require $definition);
  }

  $container = $builder->build();

  return $container;
})();
