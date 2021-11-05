<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

class PubRec implements \Mqtt\Protocol\Entity\Packet\IPacket {

  use \Mqtt\Protocol\Entity\Packet\TIdentifiable;
  use \Mqtt\Protocol\Entity\Packet\TPacket;

  const TYPE = \Mqtt\Protocol\IPacketType::PUBREC;

}
