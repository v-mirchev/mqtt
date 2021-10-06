<?php

namespace Mqtt\Protocol\Binary\Flags;

class Connect {

  /**
   * @var bool
   */
  protected $useUsername ;

  /**
   * @var bool
   */
  protected $usePassword;

  /**
   * @var bool
   */
  protected $willRetain;

  /**
   * @var int
   */
  protected $willQoS;

  /**
   * @var bool
   */
  protected $useWill;

  /**
   * @var bool
   */
  protected $useCleanSession;

  public function __construct() {
    $this->useUsername = false;
    $this->usePassword = false;
    $this->willRetain = false;
    $this->willQoS = false;
    $this->useWill = false;
  }

  /**
   * @param bool $useUsername
   */
  public function useUsername(bool $useUsername = true) {
    $this->useUsername = $useUsername;
  }

  /**
   * @param bool $usePassword
   */
  public function usePassword(bool $usePassword = true) {
    $this->usePassword = $usePassword;
  }

  /**
   * @param bool $willRetain
   */
  public function setWillRetain(bool $willRetain = true) {
    $this->willRetain = $willRetain;
  }

  /**
   * @param int $willQoS
   */
  public function setWillQoS(int $willQoS = 0) {
    $this->willQoS = $willQoS;
  }

  /**
   * @param bool $will
   */
  public function useWill(bool $will = true) {
    $this->useWill = $will;
  }

  /**
   * @param bool $cleanSession
   */
  public function useCleanSession(bool $cleanSession = true) {
    $this->useCleanSession = $cleanSession;
  }

  /**
   * @return int
   */
  public function get() : int {
    return
      ((bool)$this->useUsername * 128) +
      ((bool)$this->useUsername * (bool)$this->usePassword * 64) +
      ((bool)$this->useWill * (bool)$this->willRetain * 32) +
      ((bool)$this->useWill * ((int)$this->willQoS << 3)) +
      ((bool)$this->useWill * 4) +
      ((bool)$this->useCleanSession * 2);
  }

  public function reset() {
    $this->useUsername = false;
    $this->usePassword = false;
    $this->willRetain = false;
    $this->willQoS = false;
    $this->useWill = false;
  }

}
