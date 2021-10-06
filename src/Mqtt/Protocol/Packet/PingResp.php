<?php

namespace Mqtt\Protocol\Packet;

class PingResp implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {
  }

}
