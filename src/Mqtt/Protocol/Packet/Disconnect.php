<?php

namespace Mqtt\Protocol\Packet;

class Disconnect implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\IPacket::DISCONNECT);
  }

}
