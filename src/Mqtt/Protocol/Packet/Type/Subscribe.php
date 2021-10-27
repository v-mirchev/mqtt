<?php

namespace Mqtt\Protocol\Packet\Type;

class Subscribe implements \Mqtt\Protocol\Packet\IType {

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
    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::SUBSCRIBE);
    $frame->setReserved(0x2);
    $frame->addWord($this->id);

    foreach ($this->subscriptions as $subscription) {
      /* @var $subscription \Mqtt\Entity\Subscription */
      $frame->addString($subscription->topicFilter->filter);
      $frame->addByte($subscription->topicFilter->qos->qos);
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
