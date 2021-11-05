<?php

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class PingReq implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   */
  public function __construct(\Mqtt\Protocol\Entity\Frame $frame) {
    $this->frame = clone $frame;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\PingReq $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    /* @var $packet \Mqtt\Protocol\Entity\Packet\PingReq */

    if (! $packet->isA(\Mqtt\Protocol\IPacketType::PINGREQ)) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $packet->getType() . '> is not PINGREQ',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PINGREQ;
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PINGREQ);
  }

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get(): \Mqtt\Protocol\Entity\Frame {
    return $this->frame;
  }

}
