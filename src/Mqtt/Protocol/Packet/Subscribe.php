<?php

namespace Mqtt\Protocol\Packet;

class Subscribe implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

 /**
  * @var int
  */
  public $id;

  /**
   * @var \Mqtt\Entity\Topic[]
   */
  public $topics;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\IPacket::SUBSCRIBE);
    $frame->addVariableHeaderIdentifier($this->id);

    foreach ($this->topics as $topic) {
      /* @var $topic \Mqtt\Entity\Topic */
      $frame->addVariableHeader($topic->name);
      $frame->addVariableHeaderByte($topic->qos->qos);
    }
  }

}