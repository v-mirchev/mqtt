<?php

namespace Mqtt\Protocol\Decoder\Packet;

class PubRel implements \Mqtt\Protocol\Decoder\IPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PubRel
   */
  protected $pubRel;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\PubRel $pubRel
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\PubRel $pubRel
  ) {
    $this->identificator = clone $uint16;
    $this->pubRel = clone $pubRel;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBREL) {
      throw new \Exception('Packet type received <' . $frame->packetType . '> is not PUBREL');
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREL) {
      throw new \Mqtt\Exception\ProtocolViolation('Packet flags received do not match PUBREL reserved ones');
    }

    $this->identificator->decode($frame->payload);

    $this->pubRel = clone $this->pubRel;
    $this->pubRel->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubRel;
  }

}
