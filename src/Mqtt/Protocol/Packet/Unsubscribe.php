<?php

namespace Mqtt\Protocol\Packet;

class Unsubscribe implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

 /**
  * @var int
  */
  public $id;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\IPacket::UNSUBSCRIBE);
    $frame->addVariableHeaderIdentifier($this->id);

    foreach ($this->topics as $topic) {
      $frame->addVariableHeader($topic->name);
    }
  }

}
