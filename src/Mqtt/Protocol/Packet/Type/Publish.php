<?php

namespace Mqtt\Protocol\Packet\Type;

class Publish implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

  /**
   * @var string
   */
  public $topic;

 /**
  * @var type
  */
  public $content;

 /**
  * @var int
  */
  public $id;

 /**
  * @var int
  */
  public $qos = 0;

 /**
  * @var bool
  */
  public $retain = false;

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::PUBLISH);
    $frame->setQoS($this->qos);
    $frame->setAsRetain($this->retain);
    $frame->addString($this->topic);
    if ($this->qos > \Mqtt\Entity\IQoS::AT_MOST_ONCE) {
      $frame->addWord($this->id);
    }
    $frame->setPayload($this->content);
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::PUBLISH === $packetId;
  }

}
