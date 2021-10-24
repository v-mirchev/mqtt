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
      $container->get(\Mqtt\Protocol\Binary\Operator\Byte::class)
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

  \Mqtt\Protocol\Packet\Id\IProvider::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\Protocol\Packet\Id\Sequential::class);
  },

  \Mqtt\Session\Session::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Session\Session(
      $container->get(\Mqtt\Protocol\IProtocol::class),
      $container->get(\Mqtt\Session\StateFactory::class)
    ));
  },

  \Mqtt\Session\ISession::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\Session\Session::class);
  },

  \Mqtt\Session\IStateChanger::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\Session\Session::class);
  },

  \Mqtt\Client::class => function (\Psr\Container\ContainerInterface $container) {
    return (new \Mqtt\Client(
      $container->get(\Mqtt\Session\ISession::class)
    ));
  },

  'mqtt.protocol.packet.classmap' => [
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

  \Mqtt\Protocol\Packet\Factory::class => function (\Psr\Container\ContainerInterface $container) {
    return new \Mqtt\Protocol\Packet\Factory($container, $container->get('mqtt.protocol.packet.classmap'));
  },

  'mqtt.session.state.classmap' => [
    \Mqtt\Session\State\IState::NOT_CONNECTED => \Mqtt\Session\State\NotConnected::class,
    \Mqtt\Session\State\IState::CONNECTED => \Mqtt\Session\State\Connected::class,
    \Mqtt\Session\State\IState::CONNECTING => \Mqtt\Session\State\Connecting::class,
    \Mqtt\Session\State\IState::DISCONNECTING => \Mqtt\Session\State\Disconnecting::class,
    \Mqtt\Session\State\IState::DISCONNECTED => \Mqtt\Session\State\Disconnected::class,
    \Mqtt\Session\State\IState::STARTED => \Mqtt\Session\State\Started::class,
  ],

  \Mqtt\Session\IContext::class => function (\Psr\Container\ContainerInterface $container) {
    return new \Mqtt\Session\Context(
      $container->get(\Mqtt\Protocol\IProtocol::class),
      $container->get(\Mqtt\Entity\Configuration\Session::class)
    );
  },

  \Mqtt\Session\StateFactory::class => function (\Psr\Container\ContainerInterface $container) {
    return new \Mqtt\Session\StateFactory(
      $container,
      $container->get('mqtt.session.state.classmap'),
      $container->get(Mqtt\Session\IContext::class)
    );
  },

  \Mqtt\Protocol\Packet\Flow\IContext::class => function (\Psr\Container\ContainerInterface $container) {
    return $container->get(\Mqtt\Protocol\Packet\Flow\Context::class);
  },

  'mqtt.protocol.packet.flow.state.classmap' => [
    \Mqtt\Protocol\Packet\Flow\IState::NOT_CONNECTED => \Mqtt\Protocol\Packet\Flow\Connection\State\NotConnected::class,
    \Mqtt\Protocol\Packet\Flow\IState::CONNECTING => \Mqtt\Protocol\Packet\Flow\Connection\State\Connecting::class,
    \Mqtt\Protocol\Packet\Flow\IState::CONNECTED => \Mqtt\Protocol\Packet\Flow\Connection\State\Connected::class,
    \Mqtt\Protocol\Packet\Flow\IState::DISCONNECTED => \Mqtt\Protocol\Packet\Flow\Connection\State\Disconnected::class,

    \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_CONFIGURE => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\Configure::class,
    \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_ENABLED => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\Enabled::class,
    \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_DISABLED => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\Disabled::class,
    \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_PING_WAIT => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\PingWaiting::class,
    \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_PONG_WAIT => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\PongWaiting::class,
  ],

  \Mqtt\Protocol\Packet\Flow\Factory::class => function (\Psr\Container\ContainerInterface $container) {
    return new \Mqtt\Protocol\Packet\Flow\Factory(
      $container,
      $container->get('mqtt.protocol.packet.flow.state.classmap'),
      $container->get(\Mqtt\Protocol\Packet\Flow\IContext::class)
    );
  },

];
