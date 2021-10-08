<?php

namespace Mqtt\Protocol\Packet;

class UnsubAck implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

  /**
   * @var int
   */
  protected $id;

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {
    $this->id = $frame->getWord();
  }

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

}
