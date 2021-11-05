<?php

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class Connect implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Connect
   */
  protected $flags;

  /**
   * @var \Mqtt\Protocol\Binary\ITypedBuffer
   */
  protected $typedBuffer;

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @param \Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Connect $flags
   * @param \Mqtt\Protocol\Binary\ITypedBuffer $typedBuffer
   */
  public function __construct(
    \Mqtt\Protocol\Entity\Frame $frame,
    \Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Connect $flags,
    \Mqtt\Protocol\Binary\ITypedBuffer $typedBuffer
  ) {
    $this->frame = clone $frame;
    $this->flags = clone $flags;
    $this->typedBuffer = clone $typedBuffer;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\Connect $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    /* @var $packet \Mqtt\Protocol\Entity\Packet\Connect */

    if (! $packet->isA(\Mqtt\Protocol\IPacketType::CONNECT)) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $packet->getType() . '> is not CONNECT',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    $this->validateAuthentication($packet);
    $this->validateWill($packet);

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::CONNECT;
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_CONNECT);

    $payload = clone $this->typedBuffer;
    $payload->decorate($this->frame->payload);

    $payload->appendUtf8String($packet->protocolName);
    $payload->appendUint8($packet->protocolLevel);

    $this->flags = clone $this->flags;
    $this->buildConnectFlags($packet);
    $payload->appendUint8($this->flags->get());

    $payload->appendUint16($packet->keepAlive);
    $payload->appendUtf8String($packet->clientId);

    if ($packet->useWill) {
      $payload->appendUtf8String($packet->willTopic);
      $payload->appendUtf8String($packet->willMessage);
    }

    if ($packet->useUsername) {
      $payload->appendUtf8String($packet->username);
    }
    if ($packet->usePassword) {
      $payload->appendUtf8String($packet->password);
    }

  }

  public function buildConnectFlags(\Mqtt\Protocol\Entity\Packet\Connect $packet) {
    $this->flags->useCleanSession($packet->cleanSession);
    $this->flags->useUsername($packet->useUsername);
    $this->flags->usePassword($packet->usePassword);
    if ($packet->useWill) {
      $this->flags->useWill();
      $this->flags->setWillQoS($packet->willQos);
      $this->flags->setWillRetain($packet->willRetain);
    }
  }

  public function validateWill(\Mqtt\Protocol\Entity\Packet\Connect $packet) {
    if (!$packet->useWill) {
      if (
        !empty($packet->willMessage) ||
        !empty($packet->willTopic) ||
        $packet->willQos ||
        $packet->willRetain
      ) {
        throw new \Mqtt\Exception\ProtocolViolation(
          'Incorrect Will setup in CONNECT',
          \Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_WILL_SETUP
        );
      }
    }
  }

  public function validateAuthentication(\Mqtt\Protocol\Entity\Packet\Connect $packet) {
    if (!$packet->useUsername) {
      if (
        !empty($packet->useUsername) ||
        !empty($packet->password) ||
        $packet->usePassword
      ) {
        throw new \Mqtt\Exception\ProtocolViolation(
          'Incorrect Authentication setup in CONNECT',
          \Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_AUTHENTICATION_SETUP
        );
      }
    }

    if (!$packet->usePassword && !empty($packet->password)) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Incorrect Authentication setup in CONNECT',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_AUTHENTICATION_SETUP
      );
    }
  }

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get(): \Mqtt\Protocol\Entity\Frame {
    return $this->frame;
  }

}
