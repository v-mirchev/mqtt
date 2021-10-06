<?php

namespace Mqtt\Protocol\Packet;

class PingReq implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\IPacket::PINGREQ);
    $frame->setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE);
  }

}
