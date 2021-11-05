<?php

namespace Mqtt\Protocol\Entity\Packet;

class Unsubscribe implements \Mqtt\Protocol\Entity\Packet\IPacket {

  use \Mqtt\Protocol\Entity\Packet\TIdentifiable;
  use \Mqtt\Protocol\Entity\Packet\TPacket;

  const TYPE = \Mqtt\Protocol\IPacketType::UNSUBSCRIBE;

  /**
   * @var string[]
   */
  public $topics;
}
