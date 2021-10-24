<?php

namespace Mqtt\Protocol\Binary\Flags;

class ConnAck {

  /**
   * @var \Mqtt\Protocol\Binary\Operator\Word
   */
  protected $word;

  public function __construct(\Mqtt\Protocol\Binary\Operator\Word $word) {
    $this->word = clone $word;
  }

  public function set($value) {
    $this->word->set($value);
  }

  /**
   * @return bool
   */
  public function getSessionPresent(): bool {
    return $this->word->getMsb()->getBit(0);
  }

  /**
   * @return int
   */
  public function getReturnCode() {
    return $this->word->getLsb()->get();
  }

  public function __clone() {
    $this->word = clone $this->word;
  }

}
