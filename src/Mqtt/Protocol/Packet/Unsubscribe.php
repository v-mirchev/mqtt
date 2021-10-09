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
    return \Mqtt\Protocol\IPacket::UNSUBSCRIBE === $packetId;
  }

}
