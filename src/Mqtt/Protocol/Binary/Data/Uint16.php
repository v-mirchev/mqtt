<?php declare(strict_types = 1);

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
    $this->lsb = $uint8;
    $this->msb = $uint8;
    $this->bit = $bit;
  }

  /**
   * @param int $value
   * @return $this
   */
  public function set($value) : \Mqtt\Protocol\Binary\Data\IUint {
    if ($value instanceof \Mqtt\Protocol\Binary\Data\IUint) {
      $this->set($value->get());
    } else {
      $this->msb->set($value >> 8);
      $this->lsb->set($value % 256);
    }
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

    $this->bit = clone $this->bit;
    $this->bit->uint($this);
  }

  /**
   * @return \Mqtt\Protocol\Binary\Data\Bit
   */
  public function bits(): \Mqtt\Protocol\Binary\Data\Bit {
    return $this->bit->uint($this);
  }

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @return void
   */
  public function decode(\Mqtt\Protocol\Binary\IBuffer $buffer): void {
    $this->msb->set($buffer->getChar());
    $this->lsb->set($buffer->getChar());
  }

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @return void
   */
  public function encode(\Mqtt\Protocol\Binary\IBuffer $buffer): void {
    $buffer->append((string) $this);
  }
}
