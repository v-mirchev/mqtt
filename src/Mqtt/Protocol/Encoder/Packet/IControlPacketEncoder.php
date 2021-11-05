<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet;

interface IControlPacketEncoder {

  /**
   * @param \Mqtt\Protocol\Entity\Packet\IPacket $packet
   * @return void
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet) : void;

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get() : \Mqtt\Protocol\Entity\Frame;

}
