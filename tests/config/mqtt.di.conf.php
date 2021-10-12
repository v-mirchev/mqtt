<?php

return [

  \Mqtt\Protocol\Packet\Factory::class => function (\Psr\Container\ContainerInterface $container) {
    return new \Mqtt\Protocol\Packet\Factory($container, $container->get('mqtt.classmap'));
  },

  \Mqtt\Connection\IConnection::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\Connection\Connection::class);
  },

  \Mqtt\Protocol\Binary\IFixedHeader::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Protocol\Binary\FixedHeader(
      $container->get(\Mqtt\Protocol\Binary\Byte::class)
    ));
  },

  \Mqtt\Protocol\Protocol::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Protocol\Protocol(
      $container->get(\Mqtt\Connection\Connection::class),
      $container->get(\Mqtt\Protocol\Binary\Frame::class),
      $container->get(\Mqtt\Protocol\Packet\Factory::class)
    ));
  },

  \Mqtt\Protocol\IProtocol::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\Protocol\Protocol::class);
  },

  \Mqtt\Protocol\Packet\Id\IProvider::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\Protocol\Packet\Id\Sequential::class);
  },

  'mqtt.classmap' => [
    \Mqtt\Protocol\Packet\IType::CONNECT => \Mqtt\Protocol\Packet\Type\Connect::class,
    \Mqtt\Protocol\Packet\IType::CONNACK => \Mqtt\Protocol\Packet\Type\ConnAck::class,
    \Mqtt\Protocol\Packet\IType::PINGREQ => \Mqtt\Protocol\Packet\Type\PingReq::class,
    \Mqtt\Protocol\Packet\IType::PINGRESP => \Mqtt\Protocol\Packet\Type\PingResp::class,
    \Mqtt\Protocol\Packet\IType::DISCONNECT => \Mqtt\Protocol\Packet\Type\Disconnect::class,
    \Mqtt\Protocol\Packet\IType::PUBLISH => \Mqtt\Protocol\Packet\Type\Publish::class,
    \Mqtt\Protocol\Packet\IType::PUBACK => \Mqtt\Protocol\Packet\Type\PubAck::class,
    \Mqtt\Protocol\Packet\IType::PUBCOMP => \Mqtt\Protocol\Packet\Type\PubComp::class,
    \Mqtt\Protocol\Packet\IType::PUBREC => \Mqtt\Protocol\Packet\Type\PubRec::class,
    \Mqtt\Protocol\Packet\IType::PUBREL => \Mqtt\Protocol\Packet\Type\PubRel::class,
    \Mqtt\Protocol\Packet\IType::SUBSCRIBE => \Mqtt\Protocol\Packet\Type\Subscribe::class,
    \Mqtt\Protocol\Packet\IType::SUBACK=> \Mqtt\Protocol\Packet\Type\SubAck::class,
    \Mqtt\Protocol\Packet\IType::UNSUBSCRIBE=> \Mqtt\Protocol\Packet\Type\Unsubscribe::class,
    \Mqtt\Protocol\Packet\IType::UNSUBACK=> \Mqtt\Protocol\Packet\Type\UnsubAck::class,
  ],

];
