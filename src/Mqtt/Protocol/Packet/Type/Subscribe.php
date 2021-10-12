<?php

namespace Mqtt\Protocol\Packet\Type;

class Subscribe implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

 /**
  * @var int
  */
  public $id;

  /**
   * @var \Mqtt\Entity\Topic[]
   */
  public $topics;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::SUBSCRIBE);
    $frame->addWord($this->id);

    foreach ($this->topics as $topic) {
      /* @var $topic \Mqtt\Entity\Topic */
      $frame->addString($topic->name);
      $frame->addByte($topic->qos->qos);
    }
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::SUBSCRIBE === $packetId;
  }

}
