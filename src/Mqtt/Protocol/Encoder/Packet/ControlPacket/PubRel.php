<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class PubRel implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  use \Mqtt\Protocol\Encoder\Packet\ControlPacket\TValidators;

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
    $this->frame = $frame;
    $this->id = $id;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\PubRel $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    /* @var $packet \Mqtt\Protocol\Entity\Packet\PubRel */

    $this->assertPacketIs($packet, \Mqtt\Protocol\IPacketType::PUBREL);

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PUBREL;
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREL);

    $this->id = clone $this->id;
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
