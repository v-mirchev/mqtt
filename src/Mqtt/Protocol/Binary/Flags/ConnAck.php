<?php

namespace Mqtt\Protocol\Binary\Flags;

class ConnAck {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Word
   */
  protected $word;

  const BIT_SESSION_PRESENT = 9;

  public function __construct(\Mqtt\Protocol\Binary\Data\Word $word) {
    $this->word = clone $word;
  }

  public function set($value) {
    $this->word->set($value);
  }

  /**
   * @return bool
   */
  public function getSessionPresent(): bool {
    return $this->word->getMsb()->getBit(static::BIT_SESSION_PRESENT);
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
