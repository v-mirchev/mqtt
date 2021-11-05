<?php

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class PubRec implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $id;

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $id
   */
  public function __construct(
    \Mqtt\Protocol\Entity\Frame $frame,
    \Mqtt\Protocol\Binary\Data\Uint16 $id
  ) {
    $this->frame = clone $frame;
    $this->id = clone $id;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\PubRec $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    /* @var $packet \Mqtt\Protocol\Entity\Packet\PubRec */

    if (! $packet->isA(\Mqtt\Protocol\IPacketType::PUBREC)) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $packet->getType() . '> is not PUBREC',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PUBREC;
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREC);

    $this->id->set($packet->getId());
    $this->id->encode($this->frame->payload);
  }

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get(): \Mqtt\Protocol\Entity\Frame {
    return $this->frame;
  }

}
