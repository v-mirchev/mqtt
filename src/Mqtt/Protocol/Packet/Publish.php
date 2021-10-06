<?php

namespace Mqtt\Protocol\Packet;

class Publish implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

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
    $frame->setPacketType(\Mqtt\Protocol\IPacket::PUBLISH);
    $frame->setQoS($this->qos);
    $frame->setAsRetain($this->retain);
    $frame->addVariableHeader($this->topic);
    if ($this->qos > \Mqtt\Entity\IQoS::AT_MOST_ONCE) {
      $frame->addVariableHeaderIdentifier($this->id);
    }
    $frame->setPayload($this->content);
  }

}
