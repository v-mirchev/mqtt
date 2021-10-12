<?php

namespace Mqtt\Protocol\Packet\Type;

class PingResp implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::PINGRESP === $packetId;
  }

}
