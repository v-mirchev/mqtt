<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

class Disconnect implements \Mqtt\Protocol\Entity\Packet\IPacket {

  use \Mqtt\Protocol\Entity\Packet\TUnidentifiable;
  use \Mqtt\Protocol\Entity\Packet\TPacket;

  const TYPE = \Mqtt\Protocol\IPacketType::DISCONNECT;

}
