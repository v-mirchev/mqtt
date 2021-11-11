<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class Connect implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  use \Mqtt\Protocol\Encoder\Packet\ControlPacket\TValidators;

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

    $this->assertPacketIs($packet, \Mqtt\Protocol\IPacketType::CONNECT);

    $this->validateClientId($packet);
    $this->validateAuthentication($packet);
    $this->validateWill($packet);

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::CONNECT;
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_CONNECT);

    $payload = clone $this->typedBuffer;
    $payload->decorate($this->frame->payload);

    $payload->appendUtf8String($packet->protocolName);
    $payload->appendUint8($packet->protocolLevel);

    $this->encodeConnectFlags($packet);

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

  /**
   * @param \Mqtt\Protocol\Entity\Packet\Connect $packet
   */
  public function encodeConnectFlags(\Mqtt\Protocol\Entity\Packet\Connect $packet) {
    $this->flags = clone $this->flags;

    $this->flags->useCleanSession = $packet->cleanSession;
    $this->flags->useUsername = $packet->useUsername;
    $this->flags->usePassword = $packet->usePassword;
    $this->flags->useWill = $packet->useWill;
    $this->flags->willQoS = $packet->willQos;
    $this->flags->willRetain = $packet->willRetain;

    $this->flags->encode($this->frame->payload);
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\Connect $packet
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function validateClientId(\Mqtt\Protocol\Entity\Packet\Connect $packet) {
    if ($packet->clientId === '') {
      if (!$packet->cleanSession) {
        throw new \Mqtt\Exception\ProtocolViolation(
          'Incorrect Will setup in CONNECT',
          \Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_CLIENTID_SETUP
        );
      }
    }
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\Connect $packet
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function validateWill(\Mqtt\Protocol\Entity\Packet\Connect $packet) {
    if (!$packet->useWill) {
      if (
        $packet->willMessage !== '' ||
        $packet->willTopic !== '' ||
        $packet->willQos ||
        $packet->willRetain
      ) {
        throw new \Mqtt\Exception\ProtocolViolation(
          'Incorrect Will setup in CONNECT',
          \Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_WILL_SETUP
        );
      }
    } elseif (
      $packet->willMessage === '' ||
      $packet->willTopic === ''
    ) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Incorrect Will setup in CONNECT',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_WILL_SETUP
      );
    }
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\Connect $packet
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function validateAuthentication(\Mqtt\Protocol\Entity\Packet\Connect $packet) {
    if (!$packet->useUsername) {
      if (
        $packet->username !== '' ||
        $packet->password !== '' ||
        $packet->usePassword
      ) {
        throw new \Mqtt\Exception\ProtocolViolation(
          'Incorrect Authentication setup in CONNECT',
          \Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_AUTHENTICATION_SETUP
        );
      }
    } elseif ($packet->username === '') {
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
