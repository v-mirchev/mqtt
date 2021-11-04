<?php

namespace Mqtt\Protocol\Decoder;

interface IPacketDecoder {

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame) : void;

  /**
   * @return \Mqtt\Protocol\Entity\Packet\IPacket
   */
  public function get() : \Mqtt\Protocol\Entity\Packet\IPacket;

}
