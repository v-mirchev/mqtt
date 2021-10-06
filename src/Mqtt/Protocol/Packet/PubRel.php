<?php

namespace Mqtt\Protocol\Packet;

class PubRel implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

 /**
  * @var int
  */
  public $id;

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\IPacket::PUBREL);
    $frame->addVariableHeaderIdentifier($this->id);
  }

}
