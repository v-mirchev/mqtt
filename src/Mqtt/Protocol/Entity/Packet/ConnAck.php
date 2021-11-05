<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

class ConnAck implements \Mqtt\Protocol\Entity\Packet\IPacket {

  use \Mqtt\Protocol\Entity\Packet\TUnidentifiable;
  use \Mqtt\Protocol\Entity\Packet\TPacket;

  const TYPE = \Mqtt\Protocol\IPacketType::CONNACK;

  const CODE_CONNECTION_ACCEPTED = 0;
  const CODE_CONNECTION_REFUSED_PROTOCOL_VERSION = 1;
  const CODE_CONNECTION_REFUSED_IDENTIFIER_REJECTED = 2;
  const CODE_CONNECTION_REFUSED_SERVER_UNAVAILABLE = 3;
  const CODE_CONNECTION__REFUSED_NOT_AUTHENTICATED = 4;
  const CODE_CONNECTION__REFUSED_NOT_AUTHORIZED = 5;

  const CODE_MAX_VALUE = 5;

  /**
   * @var int
   */
  public $code;

  /**
   * @var string
   */
  public $message;

  /**
   * @var bool
   */
  public $isSessionPresent;


}
