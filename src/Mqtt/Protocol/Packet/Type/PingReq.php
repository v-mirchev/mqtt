<?php

namespace Mqtt\Protocol\Packet\Type;

class PingReq implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::PINGREQ);
    $frame->setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE);
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::PINGREQ === $packetId;
  }

}
