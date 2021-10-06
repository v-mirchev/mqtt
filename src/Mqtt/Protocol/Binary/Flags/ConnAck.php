<?php

namespace Mqtt\Protocol\Binary\Flags;

class ConnAck {

  /**
   * @var bool
   */
  protected $sessionPresent;

  /**
   * @var int
   */
  protected $returnCode;

  public function __construct() {
    $this->sessionPresent = false;
    $this->returnCode = null;
  }

  public function set(string $bytes) {
    if (strlen($bytes) != 2) {
      throw new \Exception('ConnAck variable header length mismatch');
    }
    $byte1 = \ord($bytes[0]);
    $byte2 = \ord($bytes[1]);

    $this->sessionPresent = (bool)$byte1 & 0x01;
    $this->returnCode = (int)$byte2;
  }

  /**
   * @return bool
   */
  public function getSessionPresent(): bool {
    return $this->sessionPresent;
  }

  /**
   * @return int
   */
  public function getReturnCode() {
    return $this->returnCode;
  }

  public function __clone() {
    $this->sessionPresent = false;
    $this->returnCode = null;
  }

}
