<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class ConnAck implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  use \Mqtt\Protocol\Decoder\Packet\ControlPacket\TValidators;

  const CODES = [
    0 => 'Connection Accepted',
    1 => 'Connection Refused, unacceptable protocol version',
    2 => 'Connection Refused, identifier rejected',
    3 => 'Connection Refused, server unavailable',
    4 => 'Connection Refused, bad user name or password',
    5 => 'Connection Refused, not authorized',
  ];

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\Flags\ConnAck
   */
  protected $flags;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $code;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\ConnAck
   */
  protected $connAck;

  /**
   * @param \Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags\ConnAck $flags
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $code
   * @param \Mqtt\Protocol\Entity\Packet\ConnAck $connAck
   */
  public function __construct(
    \Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags\ConnAck $flags,
    \Mqtt\Protocol\Binary\Data\Uint8 $code,
    \Mqtt\Protocol\Entity\Packet\ConnAck $connAck
  ) {
    $this->flags = $flags;
    $this->code = $code;
    $this->connAck = $connAck;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->assertPacketIs($frame, \Mqtt\Protocol\IPacketType::CONNACK);
    $this->assertPacketFlags($frame, \Mqtt\Protocol\IPacketReservedBits::FLAGS_CONNACK);

    $this->flags->decode($frame->payload);
    $this->code->decode($frame->payload);

    if ($this->code->get() > \Mqtt\Protocol\Entity\Packet\ConnAck::CODE_MAX_VALUE) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Unknown connection return code in CONNACK',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONNACK_RETURN_CODE
      );
    }

    $this->assertPayloadConsumed($frame);

    $this->connAck = clone $this->connAck;
    $this->connAck->isSessionPresent = $this->flags->getSessionPresent();
    $this->connAck->code = $this->code->get();
    $this->connAck->message = static::CODES[$this->connAck->code];
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->connAck;
  }

}
