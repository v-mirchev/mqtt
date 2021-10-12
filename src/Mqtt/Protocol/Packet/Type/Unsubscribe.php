<?php

namespace Mqtt\Protocol\Packet\Type;

class Unsubscribe implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

 /**
  * @var int
  */
  public $id;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::UNSUBSCRIBE);
    $frame->addWord($this->id);

    foreach ($this->topics as $topic) {
      $frame->addString($topic->name);
    }
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::UNSUBSCRIBE === $packetId;
  }

}
