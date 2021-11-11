<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class Disconnect implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  use \Mqtt\Protocol\Encoder\Packet\ControlPacket\TValidators;

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
   * @param \Mqtt\Protocol\Entity\Packet\Disconnect $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    /* @var $packet \Mqtt\Protocol\Entity\Packet\Disconnect */

    $this->assertPacketIs($packet, \Mqtt\Protocol\IPacketType::DISCONNECT);

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::DISCONNECT;
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_DISCONNECT);
  }

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get(): \Mqtt\Protocol\Entity\Frame {
    return $this->frame;
  }

}
