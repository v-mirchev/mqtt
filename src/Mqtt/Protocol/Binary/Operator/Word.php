<?php

namespace Mqtt\Protocol\Binary\Operator;

class Word {

  /**
   * @var \Mqtt\Protocol\Binary\Operator\Byte
   */
  protected $lsb;

  /**
   * @var \Mqtt\Protocol\Binary\Operator\Byte
   */
  protected $msb;

  public function __construct(\Mqtt\Protocol\Binary\Operator\Byte $byte) {
    $this->lsb = clone $byte;
    $this->msb = clone $byte;
  }

  /**
   * @return Byte
   */
  public function getMsb() {
    return $this->msb;
  }

  /**
   * @return Byte
   */
  public function getLsb() {
    return $this->lsb;
  }

  /**
   * @param int $value
   * @return \Mqtt\Protocol\Binary\Operator\Word
   */
  public function set(int $value) : Word {
    $this->msb->set($value >> 8);
    $this->lsb->set($value % 256);
    return $this;
  }

  /**
   * @param mixed $msb
   * @param mixed $lsb
   * @return \Mqtt\Protocol\Binary\Operator\Word
   */
  public function setBytes($msb, $lsb) : Word {
    $this->msb->set($msb);
    $this->lsb->set($lsb);
    return $this;
  }

  /**
   * @return int
   */
  public function get(): int {
    return 0xFFFF & ($this->lsb->get() + ($this->msb->get() << 8));
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return $this->msb . $this->lsb;
  }

  public function __clone() {
    $this->msb = clone $this->msb;
    $this->lsb = clone $this->lsb;
  }

}
