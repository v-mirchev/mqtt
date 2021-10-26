<?php

namespace Mqtt\Protocol\Packet\Type;

class Subscribe implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

  /**
  * @var int
  */
  public $id;

  /**
   * @var \Mqtt\Entity\Subsription[]
   */
  public $subscriptions;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::SUBSCRIBE);
    $frame->setReserved(0x2);
    $frame->addWord($this->id);

    foreach ($this->subscriptions as $subscription) {
      /* @var $subscription \Mqtt\Entity\Subsription */
      $frame->addString($subscription->topic->name);
      $frame->addByte($subscription->topic->qos->qos);
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
