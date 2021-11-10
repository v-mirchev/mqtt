<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags;

class Connect {

  const BIT_CLEAN_SESSION = 1;
  const BIT_WILL = 2;
  const BIT_WILL_QOS_LS = 3;
  const BIT_WILL_QOS_MS = 4;
  const BIT_WILL_REATIN = 5;
  const BIT_PASSWORD = 6;
  const BIT_USERNAME = 7;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $flags;

  /**
   * @var bool
   */
  public $useCleanSession;

  /**
   * @var bool
   */
  public $usePassword;

  /**
   * @var bool
   */
  public $useUsername;

  /**
   * @var bool
   */
  public $useWill;

  /**
   * @var bool
   */
  public $willQoS;

  /**
   * @var bool
   */
  public $willRetain;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $flags
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $flags) {
    $this->flags = clone $flags;

    $this->useCleanSession = false;
    $this->usePassword = false;
    $this->useUsername = false;
    $this->useWill = false;
    $this->willQos = 0;
    $this->willRetain = false;
  }

  public function __clone() {
    $this->flags = clone $this->flags;

    $this->useCleanSession = false;
    $this->usePassword = false;
    $this->useUsername = false;
    $this->useWill = false;
    $this->willQos = 0;
    $this->willRetain = false;
  }

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   */
  public function encode(\Mqtt\Protocol\Binary\IBuffer $buffer) {
    $flags = clone $this->flags;
    $flags->bits()->setBit(static::BIT_USERNAME, $this->useUsername);
    $flags->bits()->setBit(static::BIT_PASSWORD, $this->usePassword);
    $flags->bits()->setBit(static::BIT_WILL_REATIN, $this->willRetain);
    $flags->bits()->setSub(static::BIT_WILL_QOS_LS, static::BIT_WILL_QOS_MS, $this->willQoS);
    $flags->bits()->setBit(static::BIT_WILL, $this->useWill);
    $flags->bits()->setBit(static::BIT_CLEAN_SESSION, $this->useCleanSession);

    $flags->encode($buffer);
  }

}
