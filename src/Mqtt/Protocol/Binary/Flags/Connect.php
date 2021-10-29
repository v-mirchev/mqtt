<?php

namespace Mqtt\Protocol\Binary\Flags;

class Connect {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Byte
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

  public function __construct(\Mqtt\Protocol\Binary\Data\Byte $byte) {
    $this->byte = clone $byte;
  }

  /**
   * @param bool $useUsername
   */
  public function useUsername(bool $useUsername = true) {
    $this->byte->setBit(static::BIT_USERNAME, $useUsername);
  }

  /**
   * @param bool $usePassword
   */
  public function usePassword(bool $usePassword = true) {
    $this->byte->setBit(static::BIT_PASSWORD, $usePassword);
  }

  /**
   * @param bool $willRetain
   */
  public function setWillRetain(bool $willRetain = true) {
    $this->byte->setBit(static::BIT_WILL_REATIN, $willRetain);
  }

  /**
   * @param int $willQoS
   */
  public function setWillQoS(int $willQoS = 0) {
    $this->byte->setSub(static::BIT_WILL_QOS_LS, static::BIT_WILL_QOS_MS, $willQoS);
  }

  /**
   * @param bool $will
   */
  public function useWill(bool $will = true) {
    $this->byte->setBit(static::BIT_WILL, $will);
  }

  /**
   * @param bool $cleanSession
   */
  public function useCleanSession(bool $cleanSession = true) {
    $this->byte->setBit(static::BIT_CLEAN_SESSION, $cleanSession);
  }

  /**
   * @return int
   */
  public function get() : int {
    if (!$this->byte->getBit(static::BIT_USERNAME)) {
      $this->byte->setBit(static::BIT_PASSWORD, false);
    }
    return $this->byte->get();
  }

  public function reset() {
    $this->byte->set(0);
  }

}
