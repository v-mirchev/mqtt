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

  \Mqtt\PacketIdProvider\IPacketIdProvider::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\PacketIdProvider\Sequential::class);
  },

  'mqtt.classmap' => [
    \Mqtt\Protocol\IPacket::CONNECT => \Mqtt\Protocol\Packet\Connect::class,
    \Mqtt\Protocol\IPacket::CONNACK => \Mqtt\Protocol\Packet\ConnAck::class,
    \Mqtt\Protocol\IPacket::PINGREQ => \Mqtt\Protocol\Packet\PingReq::class,
    \Mqtt\Protocol\IPacket::PINGRESP => \Mqtt\Protocol\Packet\PingResp::class,
    \Mqtt\Protocol\IPacket::DISCONNECT => \Mqtt\Protocol\Packet\Disconnect::class,
    \Mqtt\Protocol\IPacket::PUBLISH => \Mqtt\Protocol\Packet\Publish::class,
    \Mqtt\Protocol\IPacket::PUBACK => \Mqtt\Protocol\Packet\PubAck::class,
    \Mqtt\Protocol\IPacket::PUBCOMP => \Mqtt\Protocol\Packet\PubComp::class,
    \Mqtt\Protocol\IPacket::PUBREC => \Mqtt\Protocol\Packet\PubRec::class,
    \Mqtt\Protocol\IPacket::PUBREL => \Mqtt\Protocol\Packet\PubRel::class,
    \Mqtt\Protocol\IPacket::SUBSCRIBE => \Mqtt\Protocol\Packet\Subscribe::class,
    \Mqtt\Protocol\IPacket::SUBACK=> \Mqtt\Protocol\Packet\SubAck::class,
    \Mqtt\Protocol\IPacket::UNSUBSCRIBE=> \Mqtt\Protocol\Packet\Unsubscribe::class,
    \Mqtt\Protocol\IPacket::UNSUBACK=> \Mqtt\Protocol\Packet\UnsubAck::class,
  ],

];
