<?php

return [

  \Mqtt\Entity\Configuration\Authentication::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Entity\Configuration\Authentication())->
      password($container->get('mqtt.auth.password'))->
      username($container->get('mqtt.auth.username'));
  },

  \Mqtt\Entity\Configuration\Server::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Entity\Configuration\Server())->
      host($container->get('mqtt.server.host'))->
      port($container->get('mqtt.server.port'))->
      connectTimeout($container->get('mqtt.server.connectTimeout'))->
      timeout($container->get('mqtt.server.timeout'));
  },

  \Mqtt\Entity\Configuration\Will::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Entity\Configuration\Will())->
        atMostOnce()->
        topic($container->get('mqtt.session.will.topic'))->
        content($container->get('mqtt.session.will.content'));
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

      $session->will();
      $session->authentication();

      return $session;
  },

  \Mqtt\Connection\Socket::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Connection\Socket(
      $container->get(\Mqtt\Entity\Configuration\Server::class)
    ));
  },

  \Mqtt\Connection\Connection::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Connection\Connection(
      $container->get(\Mqtt\Connection\Socket::class)
    ));
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

  \Mqtt\Entity\Configuration\Will::class => function (\Psr\Container\ContainerInterface $container) {

    $will = new \Mqtt\Entity\Configuration\Will(
      $container->get(\Mqtt\Entity\QoS::class),
      $container->get(\Mqtt\Entity\Message::class)
    );

    $will->
      atLeastOnce()->
      topic($container->get('mqtt.session.will.topic'))->
      content($container->get('mqtt.session.will.content'))->
      retain($container->get('mqtt.session.will.retain'));

    return $will;
  },

  \Mqtt\Session\State\Connection\Disconnected::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Session\State\Connection\Disconnected(
      $container->get(\Mqtt\Entity\Configuration\Session::class),
      $container->get(\Mqtt\Entity\Configuration\Authentication::class),
      $container->get(\Mqtt\Entity\Configuration\Will::class)
    ));
  },

  \Mqtt\Session\State\Connection\Connected::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Session\State\Connection\Connected());
  },

  \Mqtt\PacketIdProvider\IPacketIdProvider::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\PacketIdProvider\Sequential::class);
  },

  \Mqtt\Session\Session::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Session\Session(
      $container->get(\Mqtt\Protocol\IProtocol::class),
      \Mqtt\Session\State\ISessionState::DISCONNECTED,
      $container->get(\Mqtt\PacketIdProvider\IPacketIdProvider::class),
      $container->get(\Mqtt\Session\State\Factory::class),
      $container->get(\Mqtt\Session\State\Context::class)
    ));
  },

  \Mqtt\Session\KeptAliveSession::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Session\KeptAliveSession(
      $container->get(\Mqtt\Entity\Configuration\Session::class),
      $container->get(\Mqtt\Protocol\IProtocol::class),
      $container->get(\Mqtt\Session\Session::class),
      \Mqtt\Session\State\ISessionState::PING_WAIT,
      $container->get(\Mqtt\Session\State\Factory::class),
      $container->get(\Mqtt\Session\State\Context::class),
      $container->get(\Mqtt\Timeout::class),
    ));
  },

  \Mqtt\Session\ISession::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\Session\KeptAliveSession::class);
  },

  \Mqtt\Client::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Client(
      $container->get(\Mqtt\Session\ISession::class)
    ));
  },

  'mqtt.protocol.packet.classmap' => [
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

  \Mqtt\Protocol\Packet\Factory::class => function (\Psr\Container\ContainerInterface $container) {
    return new \Mqtt\Protocol\Packet\Factory($container, $container->get('mqtt.protocol.packet.classmap'));
  },

  'mqtt.session.state.classmap' => [
    \Mqtt\Session\State\ISessionState::CONNECTED => \Mqtt\Session\State\Connection\Connected::class,
    \Mqtt\Session\State\ISessionState::DISCONNECTED => \Mqtt\Session\State\Connection\Disconnected::class,
    \Mqtt\Session\State\ISessionState::PING_WAIT => \Mqtt\Session\State\Connection\PingWaiting::class,
    \Mqtt\Session\State\ISessionState::PONG_WAIT => \Mqtt\Session\State\Connection\PongWaiting::class,
  ],

  \Mqtt\Session\State\Factory::class => function (\Psr\Container\ContainerInterface $container) {
    return new \Mqtt\Session\State\Factory($container, $container->get('mqtt.session.state.classmap'));
  },

];
