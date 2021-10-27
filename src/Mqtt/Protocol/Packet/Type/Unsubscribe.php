<?php

namespace Mqtt\Protocol\Packet\Type;

class Unsubscribe implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

 /**
  * @var int
  */
  public $id;

  /**
   * @var \Mqtt\Entity\Subscription[]
   */
  public $subscriptions;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::UNSUBSCRIBE);
    $frame->setReserved(0x2);
    $frame->addWord($this->id);

    foreach ($this->subscriptions as $subscription) {
      $frame->addString($subscription->topicFilter->filter);
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
