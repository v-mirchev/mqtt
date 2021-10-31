<?php

namespace Mqtt\Protocol\Packet\Type\Flags;

class Connect {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $byte;

  const BIT_CLEAN_SESSION = 1;
  const BIT_WILL = 2;
  const BIT_WILL_QOS_LS = 3;
  const BIT_WILL_QOS_MS = 4;
  const BIT_WILL_REATIN = 5;
  const BIT_PASSWORD = 6;
  const BIT_USERNAME = 7;

  /**
   * @var bool
   */
  protected $useCleanSession;

  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $byte) {
    $this->byte = clone $byte;
  }

  /**
   * @param bool $useUsername
   */
  public function useUsername(bool $useUsername = true) {
    $this->byte->bits()->setBit(static::BIT_USERNAME, $useUsername);
  }

  /**
   * @param bool $usePassword
   */
  public function usePassword(bool $usePassword = true) {
    $this->byte->bits()->setBit(static::BIT_PASSWORD, $usePassword);
  }

  /**
   * @param bool $willRetain
   */
  public function setWillRetain(bool $willRetain = true) {
    $this->byte->bits()->setBit(static::BIT_WILL_REATIN, $willRetain);
  }

  /**
   * @param int $willQoS
   */
  public function setWillQoS(int $willQoS = 0) {
    $this->byte->bits()->setSub(static::BIT_WILL_QOS_LS, static::BIT_WILL_QOS_MS, $willQoS);
  }

  /**
   * @param bool $will
   */
  public function useWill(bool $will = true) {
    $this->byte->bits()->setBit(static::BIT_WILL, $will);
  }

  /**
   * @param bool $cleanSession
   */
  public function useCleanSession(bool $cleanSession = true) {
    $this->byte->bits()->setBit(static::BIT_CLEAN_SESSION, $cleanSession);
  }

  /**
   * @return int
   */
  public function get() : int {
    if (!$this->byte->bits()->getBit(static::BIT_USERNAME)) {
      $this->byte->bits()->setBit(static::BIT_PASSWORD, false);
    }
    return $this->byte->bits()->get();
  }

  public function reset() {
    $this->byte->bits()->set(0);
  }

  public function __clone() {
    $this->byte = clone $this->byte;
  }

}
