<?php

namespace Mqtt\Protocol\Decoder\Packet;

class PubComp implements \Mqtt\Protocol\Decoder\IPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PubComp
   */
  protected $pubComp;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\PubComp $pubComp
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\PubComp $pubComp
  ) {
    $this->identificator = clone $uint16;
    $this->pubComp = clone $pubComp;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBCOMP) {
      throw new \Exception('Packet type received <' . $frame->packetType . '> is not PUBCOMP');
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBCOMP) {
      throw new \Mqtt\Exception\ProtocolViolation('Packet flags received do not match PUBCOMP reserved ones');
    }

    $this->identificator->decode($frame->payload);

    $this->pubComp = clone $this->pubComp;
    $this->pubComp->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubComp;
  }

}
