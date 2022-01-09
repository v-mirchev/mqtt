<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

class Publish implements \Mqtt\Protocol\Entity\Packet\IPacket {

  use \Mqtt\Protocol\Entity\Packet\TIdentifiable;
  use \Mqtt\Protocol\Entity\Packet\TPacket;

  const TYPE = \Mqtt\Protocol\IPacketType::PUBLISH;

  /**
   * @var int
   */
  public $qosLevel = 0;

  /**
   * @var bool
   */
  public $isDuplicate = false;

  /**
   * @var bool
   */
  public $isRetain = false;

  /**
   * @var string
   */
  public $topic;

  /**
   * @var string
   */
  public $message;

}
