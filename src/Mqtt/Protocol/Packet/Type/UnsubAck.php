<?php

namespace Mqtt\Protocol\Packet\Type;

class UnsubAck implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

  /**
   * @var int
   */
  public $id;

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {
    $this->id = $frame->getWord();
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::UNSUBACK === $packetId;
  }

}
