<?php

namespace Mqtt\Protocol\Binary\Data;

class Uint16 implements \Mqtt\Protocol\Binary\Data\ICodec, \Mqtt\Protocol\Binary\Data\IUint {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $lsb;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $msb;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Bit
   */
  protected $bit;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $uint8
   * @param \Mqtt\Protocol\Binary\Data\Bit $bit
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint8 $uint8,
    \Mqtt\Protocol\Binary\Data\Bit $bit
  ) {
    $this->lsb = clone $uint8;
    $this->msb = clone $uint8;
    $this->bit = clone $bit;
  }

  /**
   * @return Uint8
   */
  public function getMsb() {
    return $this->msb;
  }

  /**
   * @return Uint8
   */
  public function getLsb() {
    return $this->lsb;
  }

  /**
   * @param int $value
   * @return $this
   */
  public function set($value) : \Mqtt\Protocol\Binary\Data\IUint {
    $this->msb->set($value >> 8);
    $this->lsb->set($value % 256);
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

  /**
   * @return \Mqtt\Protocol\Binary\Data\Bit
   */
  public function bits(): \Mqtt\Protocol\Binary\Data\Bit {
    return $this->bit->uint($this);
  }

  /**
   * @param \Mqtt\Protocol\Binary\Data\IBuffer $buffer
   * @return void
   */
  public function decode(\Mqtt\Protocol\Binary\Data\IBuffer $buffer): void {
    $this->msb->set($buffer->getByte());
    $this->lsb->set($buffer->getByte());
  }

  /**
   * @param \Mqtt\Protocol\Binary\Data\IBuffer $buffer
   * @return void
   */
  public function encode(\Mqtt\Protocol\Binary\Data\IBuffer $buffer): void {
    $buffer->append((string) $this);
  }
}
