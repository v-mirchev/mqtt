<?php

namespace Mqtt\Protocol\Binary;

class Byte {

  /**
   * @var int
   */
  protected $value;

  public function setFromMsb(int $value) {
    $this->set($value >> 8);
  }

  public function setFromLsb(int $value) {
    $this->set($value);
  }

  public function msb(int $msb) : int {
    return $this->get() + ($msb << 8);
  }

  /**
   * @param type $value
   * @return $this
   */
  public function set($value) : \Mqtt\Protocol\Binary\Byte {
    $this->value = is_string($value) ? \ord($value) : (0xFF & (int)$value);
    return $this;
  }

  /**
   * @return int
   */
  public function get(): int {
    return 0xFF & $this->value;
  }

  /**
   * @param int $bit
   * @param type $bitValue
   * @return void
   */
  public function setBit(int $bit, $bitValue) : \Mqtt\Protocol\Binary\Byte {
    $this->value = $bitValue ? $this->get() | (1 << $bit) : $this->get() & ~(1 << $bit);
    return $this;
  }

  /**
   * @param int $bit
   * @return int
   */
  public function getBit(int $bit) : int {
    return (bool)($this->get() & (1 << $bit));
  }

  /**
   * @param int $startBit
   * @param int $endBit
   * @return int
   */
  public function getSub(int $startBit, int $endBit) : int {
    return (int)(($this->get() & ((1 << ($endBit + 1)) - 1)) >> $startBit);
  }

  /**
   * @param int $bit
   * @return int
   */
  public function setSub(int $startBit, int $endBit, int $value) : \Mqtt\Protocol\Binary\Byte {
    $mask = (int)(((1 << ($endBit + 1)) - 1) >> $startBit) << $startBit;
    $shiftedValue = (($value << $startBit) & $mask);
    $this->value &= (~$mask);
    $this->value |= $shiftedValue;
    return $this;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return \chr($this->get());
  }

  public function __clone() {
    $this->value = 0;
  }

}
