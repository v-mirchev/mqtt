<?php

return [

  'mqtt.auth.password' => '',
  'mqtt.auth.username' => '',

  'mqtt.server.host' => '127.0.0.1',
  'mqtt.server.port' => 1883,

  'mqtt.server.connectTimeout' => 60000,
  'mqtt.server.timeout' => 30000,

  'mqtt.session.clientId' => 'pure-mqtt',
  'mqtt.session.keepAliveInterval' => 60,
  'mqtt.session.certificateFile' => null,
  'mqtt.session.will.topic' => 'lib-will-topic',
  'mqtt.session.will.content' => 'lib-will-content',
  'mqtt.session.will.retain' => true,

  \Mqtt\Entity\Configuration\Server::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Entity\Configuration\Server())->
      host($container->get('mqtt.server.host'))->
      port($container->get('mqtt.server.port'))->
      connectTimeout($container->get('mqtt.server.connectTimeout'))->
      timeout($container->get('mqtt.server.timeout'));
  },

  \Mqtt\Entity\Configuration\Session::class => function (\Psr\Container\ContainerInterface $container) {
    $session = (new \Mqtt\Entity\Configuration\Session(
        $container->get(\Mqtt\Entity\Configuration\Authentication::class),
        $container->get(\Mqtt\Entity\Configuration\Will::class),
        $container->get(\Mqtt\Entity\Configuration\Protocol::class)
      ))->
      clientId($container->get('mqtt.session.clientId'))->
      usePersistent($container->get('mqtt.session.persistent'))->
      keepAliveInterval($container->get('mqtt.session.keepAliveInterval'));

      $session->will()->
        atLeastOnce()->
        topic($container->get('mqtt.session.will.topic'))->
        content($container->get('mqtt.session.will.content'))->
        retain($container->get('mqtt.session.will.retain'));

      $session->authentication()->
        password($container->get('mqtt.auth.password'))->
        username($container->get('mqtt.auth.username'));

      return $session;
  },

];
