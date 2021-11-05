<?php

namespace Mqtt;

class Bootstrap  {

  public function getDiDefinitions() : array {
    return [

//      \Mqtt\Connection\IConnection::class => \Di\get(\Mqtt\Connection\Connection::class),
//      \Mqtt\Protocol\Binary\IFixedHeader::class => \Di\get(\Mqtt\Protocol\Binary\FixedHeader::class),
//      \Mqtt\Protocol\IProtocol::class => \Di\get(\Mqtt\Protocol\Protocol::class),
//      \Mqtt\Protocol\Packet\Id\IProvider::class => \Di\get(\Mqtt\Protocol\Packet\Id\Sequential::class),
//      \Mqtt\Session\ISession::class => \Di\get(\Mqtt\Session\Session::class),
//      \Mqtt\Session\IStateChanger::class => \Di\get(\Mqtt\Session\Session::class),
//      \Mqtt\Client\IClient::class => \Di\get(\Mqtt\Client\Client::class),
//      \Mqtt\Session\IContext::class => \Di\get(\Mqtt\Session\Context::class),
//      \Mqtt\Protocol\Packet\Flow\ISessionContext::class => \Di\get(\Mqtt\Protocol\Packet\Flow\Context::class),
//      \Mqtt\Protocol\Packet\Flow\IQueue::class => \Di\get(\Mqtt\Protocol\Packet\Flow\Queue::class),
      \Mqtt\Protocol\Binary\IBuffer::class => \Di\get(\Mqtt\Protocol\Binary\Buffer::class),
      \Mqtt\Protocol\Binary\ITypedBuffer::class => \Di\get(\Mqtt\Protocol\Binary\TypedBuffer::class),

      \Mqtt\Protocol\Decoder\Frame\IDecoder::class => \Di\get(\Mqtt\Protocol\Decoder\Frame\Decoder::class),
      \Mqtt\Protocol\Decoder\Packet\IDecoder::class => \Di\get(\Mqtt\Protocol\Decoder\Packet\Decoder::class),
      \Mqtt\Protocol\Decoder\IDecoder::class => \Di\get(\Mqtt\Protocol\Decoder\Decoder::class),

      \Mqtt\Protocol\Encoder\Frame\IEncoder::class => \Di\get(\Mqtt\Protocol\Encoder\Frame\Encoder::class),
      \Mqtt\Protocol\Encoder\Packet\IEncoder::class => \Di\get(\Mqtt\Protocol\Encoder\Packet\Encoder::class),
      \Mqtt\Protocol\Encoder\IEncoder::class => \Di\get(\Mqtt\Protocol\Encoder\Encoder::class),

      \Mqtt\Protocol\ICodec::class => \Di\get(\Mqtt\Protocol\Codec::class),

      __NAMESPACE__ . '.mqtt.protocol.frame.decoder.classmap' => [
        \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::CONTROL_HEADER_PROCESSING => \Mqtt\Protocol\Decoder\Frame\Fsm\State\ControlHeaderProcessing::class,
        \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::REMAINING_LENGTH_PROCESSING => \Mqtt\Protocol\Decoder\Frame\Fsm\State\RemainingLengthProcessing::class,
        \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::REMAINING_LENGTH_COMPLETED => \Mqtt\Protocol\Decoder\Frame\Fsm\State\RemainingLengthCompleted::class,
        \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::PAYLOAD_PROCESSING => \Mqtt\Protocol\Decoder\Frame\Fsm\State\PayloadProcessing::class,
        \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::FRAME_COMPLETED => \Mqtt\Protocol\Decoder\Frame\Fsm\State\FrameCompleted::class,
      ],

      \Mqtt\Protocol\Decoder\Frame\Fsm\Factory::class => function (\Psr\Container\ContainerInterface $container) {
        return new \Mqtt\Protocol\Decoder\Frame\Fsm\Factory(
          $container,
          $container->get(__NAMESPACE__ . '.mqtt.protocol.frame.decoder.classmap')
        );
      },

      __NAMESPACE__ . '.mqtt.protocol.packet.decoder.classmap' => [
        \Mqtt\Protocol\IPacketType::CONNACK => \Mqtt\Protocol\Decoder\Packet\ControlPacket\ConnAck::class,
        \Mqtt\Protocol\IPacketType::PINGRESP => \Mqtt\Protocol\Decoder\Packet\ControlPacket\PingResp::class,
        \Mqtt\Protocol\IPacketType::PUBACK => \Mqtt\Protocol\Decoder\Packet\ControlPacket\PubAck::class,
        \Mqtt\Protocol\IPacketType::PUBCOMP => \Mqtt\Protocol\Decoder\Packet\ControlPacket\PubComp::class,
        \Mqtt\Protocol\IPacketType::PUBREC => \Mqtt\Protocol\Decoder\Packet\ControlPacket\PubRec::class,
        \Mqtt\Protocol\IPacketType::PUBREL => \Mqtt\Protocol\Decoder\Packet\ControlPacket\PubRel::class,
        \Mqtt\Protocol\IPacketType::PUBLISH => \Mqtt\Protocol\Decoder\Packet\ControlPacket\Publish::class,
        \Mqtt\Protocol\IPacketType::SUBACK => \Mqtt\Protocol\Decoder\Packet\ControlPacket\SubAck::class,
        \Mqtt\Protocol\IPacketType::UNSUBACK => \Mqtt\Protocol\Decoder\Packet\ControlPacket\UnsubAck::class,
      ],

      \Mqtt\Protocol\Decoder\Packet\Factory::class => function (\Psr\Container\ContainerInterface $container) {
        return new \Mqtt\Protocol\Decoder\Packet\Factory(
          $container,
          $container->get(__NAMESPACE__ . '.mqtt.protocol.packet.decoder.classmap')
        );
      },

      __NAMESPACE__ . '.mqtt.protocol.packet.encoder.classmap' => [
        \Mqtt\Protocol\IPacketType::CONNECT => \Mqtt\Protocol\Encoder\Packet\ControlPacket\Connect::class,
        \Mqtt\Protocol\IPacketType::DISCONNECT => \Mqtt\Protocol\Encoder\Packet\ControlPacket\Disconnect::class,
        \Mqtt\Protocol\IPacketType::PINGREQ => \Mqtt\Protocol\Encoder\Packet\ControlPacket\PingReq::class,
        \Mqtt\Protocol\IPacketType::PUBACK => \Mqtt\Protocol\Encoder\Packet\ControlPacket\PubAck::class,
        \Mqtt\Protocol\IPacketType::PUBCOMP => \Mqtt\Protocol\Encoder\Packet\ControlPacket\PubComp::class,
        \Mqtt\Protocol\IPacketType::PUBREC => \Mqtt\Protocol\Encoder\Packet\ControlPacket\PubRec::class,
        \Mqtt\Protocol\IPacketType::PUBREL => \Mqtt\Protocol\Encoder\Packet\ControlPacket\PubRel::class,
        \Mqtt\Protocol\IPacketType::PUBLISH => \Mqtt\Protocol\Encoder\Packet\ControlPacket\Publish::class,
        \Mqtt\Protocol\IPacketType::SUBSCRIBE => \Mqtt\Protocol\Encoder\Packet\ControlPacket\Subscribe::class,
        \Mqtt\Protocol\IPacketType::UNSUBSCRIBE => \Mqtt\Protocol\Encoder\Packet\ControlPacket\Unsubscribe::class,
      ],

      \Mqtt\Protocol\Encoder\Packet\Factory::class => function (\Psr\Container\ContainerInterface $container) {
        return new \Mqtt\Protocol\Encoder\Packet\Factory(
          $container,
          $container->get(__NAMESPACE__ . '.mqtt.protocol.packet.encoder.classmap')
        );
      },

//      __NAMESPACE__ . '.mqtt.session.state.classmap' => [
//        \Mqtt\Session\State\IState::NOT_CONNECTED => \Mqtt\Session\State\NotConnected::class,
//        \Mqtt\Session\State\IState::CONNECTED => \Mqtt\Session\State\Connected::class,
//        \Mqtt\Session\State\IState::CONNECTING => \Mqtt\Session\State\Connecting::class,
//        \Mqtt\Session\State\IState::DISCONNECTING => \Mqtt\Session\State\Disconnecting::class,
//        \Mqtt\Session\State\IState::DISCONNECTED => \Mqtt\Session\State\Disconnected::class,
//        \Mqtt\Session\State\IState::STARTED => \Mqtt\Session\State\Started::class,
//      ],
//
//      \Mqtt\Session\StateFactory::class => function (\Psr\Container\ContainerInterface $container) {
//        return new \Mqtt\Session\StateFactory(
//          $container,
//          $container->get(__NAMESPACE__ . '.mqtt.session.state.classmap'),
//          $container->get(\Mqtt\Session\IContext::class)
//        );
//      },

//      __NAMESPACE__ . '.mqtt.protocol.packet.flow.state.classmap' => [
//        \Mqtt\Protocol\Packet\Flow\IState::NOT_CONNECTED => \Mqtt\Protocol\Packet\Flow\Connection\State\NotConnected::class,
//        \Mqtt\Protocol\Packet\Flow\IState::CONNECTING => \Mqtt\Protocol\Packet\Flow\Connection\State\Connecting::class,
//        \Mqtt\Protocol\Packet\Flow\IState::CONNECTED => \Mqtt\Protocol\Packet\Flow\Connection\State\Connected::class,
//        \Mqtt\Protocol\Packet\Flow\IState::DISCONNECTED => \Mqtt\Protocol\Packet\Flow\Connection\State\Disconnected::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_CONFIGURE => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\Configure::class,
//        \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_ENABLED => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\Enabled::class,
//        \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_DISABLED => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\Disabled::class,
//        \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_PING_WAIT => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\PingWaiting::class,
//        \Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_PONG_WAIT => \Mqtt\Protocol\Packet\Flow\KeepAlive\State\PongWaiting::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::SUBSCRIBE_SUBSCRIBING => \Mqtt\Protocol\Packet\Flow\Subscription\State\Subscribing::class,
//        \Mqtt\Protocol\Packet\Flow\IState::SUBSCRIBE_ACK_WAITING => \Mqtt\Protocol\Packet\Flow\Subscription\State\AckWaiting::class,
//        \Mqtt\Protocol\Packet\Flow\IState::SUBSCRIBE_ACKNOWLEDGED => \Mqtt\Protocol\Packet\Flow\Subscription\State\Acknowledged::class,
//        \Mqtt\Protocol\Packet\Flow\IState::SUBSCRIBE_UNACKNOWLEDGED => \Mqtt\Protocol\Packet\Flow\Subscription\State\Unacknowledged::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::UNSUBSCRIBE_UNSUBSCRIBING => \Mqtt\Protocol\Packet\Flow\Unsubscription\State\Unsubscribing::class,
//        \Mqtt\Protocol\Packet\Flow\IState::UNSUBSCRIBE_ACK_WAITING => \Mqtt\Protocol\Packet\Flow\Unsubscription\State\AckWaiting::class,
//        \Mqtt\Protocol\Packet\Flow\IState::UNSUBSCRIBE_ACKNOWLEDGED => \Mqtt\Protocol\Packet\Flow\Unsubscription\State\Acknowledged::class,
//        \Mqtt\Protocol\Packet\Flow\IState::UNSUBSCRIBE_UNACKNOWLEDGED => \Mqtt\Protocol\Packet\Flow\Unsubscription\State\Unacknowledged::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_PUBLISHING => \Mqtt\Protocol\Packet\Flow\Publishment\State\Publishing::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\Publishing::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_NOTIFY => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\Notify::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_AT_MOST_ONCE => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\AtMostOnce\Publishing::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_AT_LEAST_ONCE => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\AtLeastOnce\Publishing::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_EXACTLY_ONCE => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\ExactlyOnce\Publishing::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_ACKNOWLEDGED => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\AtLeastOnce\Acknowledged::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_RECEIVED => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\ExactlyOnce\Received::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_RELEASE_WAITING => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\ExactlyOnce\ReleaseWaiting::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_COMPLETED => \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\ExactlyOnce\Completed::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_PUBLISHING => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\Publishing::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_PUBLISHING_AT_MOST_ONCE => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\AtMostOnce\Publishing::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_PUBLISHING_AT_LEAST_ONCE => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\AtLeastOnce\Publishing::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_PUBLISHING_EXACTLY_ONCE => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce\Publishing::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_ACK_WAITING => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\AtLeastOnce\AckWaiting::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_ACK_REPUBLISH => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\AtLeastOnce\Republish::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_ACKNOWLEDGED => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\AtLeastOnce\Acknowledged::class,
//
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_RECEIVED_WAITING => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce\ReceivedWaiting::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_RECEIVED => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce\Received::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_RECEIVED_REPUBLISH => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce\Republish::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_COMPLETED_WAITING => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce\CompletedWaiting::class,
//        \Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_COMPLETED => \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce\Completed::class,
//      ],
//
//      \Mqtt\Protocol\Packet\Flow\Factory::class => function (\Psr\Container\ContainerInterface $container) {
//        return new \Mqtt\Protocol\Packet\Flow\Factory(
//          $container,
//          $container->get(__NAMESPACE__ . '.mqtt.protocol.packet.flow.state.classmap'),
//          $container->get(\Mqtt\Protocol\Packet\Flow\ISessionContext::class)
//        );
//      },

    ];
  }

}
