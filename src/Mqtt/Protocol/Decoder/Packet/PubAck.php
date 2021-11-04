<?php

namespace Mqtt\Protocol\Decoder\Packet;

class PubAck implements \Mqtt\Protocol\Decoder\IPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PubAck
   */
  protected $pubAck;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\PubAck $pubAck
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\PubAck $pubAck
  ) {
    $this->identificator = clone $uint16;
    $this->pubAck = clone $pubAck;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBACK) {
      throw new \Exception('Packet type received <' . $frame->packetType . '> is not PUBACK');
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBACK) {
      throw new \Mqtt\Exception\ProtocolViolation('Packet flags received do not match PUBACK reserved ones');
    }

    $this->identificator->decode($frame->payload);

    $this->pubAck = clone $this->pubAck;
    $this->pubAck->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubAck;
  }

}
