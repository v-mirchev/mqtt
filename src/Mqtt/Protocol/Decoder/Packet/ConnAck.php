<?php

namespace Mqtt\Protocol\Decoder\Packet;

class ConnAck implements \Mqtt\Protocol\Decoder\IPacketDecoder {

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
   * @param \Mqtt\Protocol\Decoder\Packet\Flags\ConnAck $flags
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $code
   * @param \Mqtt\Protocol\Entity\Packet\ConnAck $connAck
   */
  public function __construct(
    \Mqtt\Protocol\Decoder\Packet\Flags\ConnAck $flags,
    \Mqtt\Protocol\Binary\Data\Uint8 $code,
    \Mqtt\Protocol\Entity\Packet\ConnAck $connAck
  ) {
    $this->flags = clone $flags;
    $this->code = clone $code;
    $this->connAck = clone $connAck;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::CONNACK) {
      throw new \Exception('Packet type received <' . $frame->packetType . '> is not CONNACK');
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_CONNACK) {
      throw new \Mqtt\Exception\ProtocolViolation('Packet flags received do not match CONNACK reserved ones');
    }

    $this->flags->decode($frame->payload);
    $this->code->decode($frame->payload);

    $this->connAck = clone $this->connAck;
    $this->connAck->isSessionPresent = $this->flags->getSessionPresent();
    $this->connAck->code = $this->code->get();
    $this->connAck->message = static::CODES[$this->connAck->code];
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->connAck;
  }

}
