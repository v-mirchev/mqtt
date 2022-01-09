<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

class Connect implements \Mqtt\Protocol\Entity\Packet\IPacket {

  use \Mqtt\Protocol\Entity\Packet\TUnidentifiable;
  use \Mqtt\Protocol\Entity\Packet\TPacket;

  const TYPE = \Mqtt\Protocol\IPacketType::CONNECT;

  /**
   * @var string
   */
  public $protocolName = 'MQTT';

  /**
   * @var int
   */
  public $protocolLevel = 0x04;

  /**
   * @var bool
   */
  public $useUsername = false;

  /**
   * @var bool
   */
  public $usePassword = false;

  /**
   * @var bool
   */
  public $useWill = false;

  /**
   * @var bool
   */
  public $willRetain = false;

  /**
   * @var int
   */
  public $willQos = 0x00;

  /**
   * @var bool
   */
  public $cleanSession = true;

  /**
   * @var int
   */
  public $keepAlive = 0;

  /**
   * @var string
   */
  public $username = '';

  /**
   * @var string
   */
  public $password = '';

  /**
   * @var string
   */
  public $willTopic = '';

  /**
   * @var string
   */
  public $willMessage = '';

  /**
   * @var string
   */
  public $clientId = '';

}
