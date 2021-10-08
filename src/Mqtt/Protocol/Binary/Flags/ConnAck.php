<?php

namespace Mqtt\Protocol\Binary\Flags;

class ConnAck {

  /**
   * @var \Mqtt\Protocol\Binary\Word
   */
  protected $word;

  /**
   * @var bool
   */
  protected $sessionPresent;

  /**
   * @var int
   */
  protected $returnCode;

  public function __construct(\Mqtt\Protocol\Binary\Word $word) {
    $this->word = clone $word;

    $this->sessionPresent = false;
    $this->returnCode = null;
  }

  public function set($value) {
    $word = clone $this->word;
    $word->set($value);

    $this->sessionPresent = $word->getMsb()->getBit(0);
    $this->returnCode = $word->getLsb()->get();
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
