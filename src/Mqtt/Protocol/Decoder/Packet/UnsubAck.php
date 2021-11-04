<?php

namespace Mqtt\Protocol\Decoder\Packet;

class UnsubAck implements \Mqtt\Protocol\Decoder\IPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\UnsubAck
   */
  protected $unsubAck;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\UnsubAck $unsubAck
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\UnsubAck $unsubAck
  ) {
    $this->identificator = clone $uint16;
    $this->unsubAck = clone $unsubAck;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::UNSUBACK) {
      throw new \Exception('Packet type received <' . $frame->packetType . '> is not UNSUBACK');
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_UNSUBACK) {
      throw new \Mqtt\Exception\ProtocolViolation('Packet flags received do not match UNSUBACK reserved ones');
    }

    $this->identificator->decode($frame->payload);

    $this->unsubAck = clone $this->unsubAck;
    $this->unsubAck->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->unsubAck;
  }

}
