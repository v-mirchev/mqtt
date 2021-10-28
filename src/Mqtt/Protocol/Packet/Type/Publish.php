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
  * @var bool
  */
  public $dup = false;

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::PUBLISH);
    $frame->setQoS($this->qos);
    $frame->setAsRetain($this->retain);
    $frame->setAsDup($this->dup);
    $frame->addString($this->topic);
    if ($this->qos > \Mqtt\Entity\IQoS::AT_MOST_ONCE) {
      $frame->addWord($this->id);
    }
    $frame->setPayload($this->content);
  }

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {
    $this->dup = $frame->isDup();
    $this->retain = $frame->isRetain();
    $this->qos = $frame->getQoS();
    $this->topic = $frame->getString();
    if ($this->qos > \Mqtt\Entity\IQoS::AT_MOST_ONCE) {
      $this->id = $frame->getWord();
    }
    $this->content = $frame->getPayload();
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::PUBLISH === $packetId;
  }

}
