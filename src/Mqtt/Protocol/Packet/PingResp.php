<?php

namespace Mqtt\Protocol\Packet;

class PingResp implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\IPacket::PINGRESP === $packetId;
  }

}
