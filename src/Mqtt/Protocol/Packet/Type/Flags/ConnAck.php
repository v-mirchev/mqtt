<?php

namespace Mqtt\Protocol\Packet\Type\Flags;

class ConnAck {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $word;

  const BIT_SESSION_PRESENT = 0;

  public function __construct(\Mqtt\Protocol\Binary\Data\Uint16 $word) {
    $this->word = clone $word;
  }

  public function set($value) {
    $this->word->set($value);
  }

  /**
   * @return bool
   */
  public function getSessionPresent(): bool {
    return $this->word->getMsb()->bits()->getBit(static::BIT_SESSION_PRESENT);
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
