<?php

namespace Mqtt\Protocol\Decoder\Packet;

class PubRec implements \Mqtt\Protocol\Decoder\IPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PubRec
   */
  protected $pubRec;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\PubRec $pubRec
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\PubRec $pubRec
  ) {
    $this->identificator = clone $uint16;
    $this->pubRec = clone $pubRec;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBREC) {
      throw new \Exception('Packet type received <' . $frame->packetType . '> is not PUBREC');
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREC) {
      throw new \Mqtt\Exception\ProtocolViolation('Packet flags received do not match PUBREC reserved ones');
    }

    $this->identificator->decode($frame->payload);

    $this->pubRec = clone $this->pubRec;
    $this->pubRec->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubRec;
  }

}
