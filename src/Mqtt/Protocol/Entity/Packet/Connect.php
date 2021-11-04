<?php

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
  public $useUsername;

  /**
   * @var bool
   */
  public $usePassword;

  /**
   * @var bool
   */
  public $useWill;

  /**
   * @var bool
   */
  public $willRetain;

  /**
   * @var int
   */
  public $willQos;

  /**
   * @var bool
   */
  public $cleanSession;

  /**
   * @var int
   */
  public $keepAlive;

  /**
   * @var string
   */
  public $username;

  /**
   * @var string
   */
  public $password;

  /**
   * @var string
   */
  public $willTopic;

  /**
   * @var string
   */
  public $willMessage;

  /**
   * @var string
   */
  public $clientId;

}
