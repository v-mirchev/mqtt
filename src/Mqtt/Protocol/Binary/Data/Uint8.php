<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Binary\Data;

class Uint8 implements \Mqtt\Protocol\Binary\Data\ICodec, \Mqtt\Protocol\Binary\Data\IUint {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Bit
   */
  protected $bit;

  /**
   * @var int
   */
  protected $value;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Bit $bit
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Bit $bit) {
    $this->bit = $bit;
  }

  /**
   * @param type $value
   * @return $this
   */
  public function set($value) : \Mqtt\Protocol\Binary\Data\IUint {
    $this->value = $value instanceof \Mqtt\Protocol\Binary\Data\IUint ?
      $value->get() :
      (is_string($value) ? \ord($value[0]) : (0xFF & (int)$value));
    return $this;
  }

  /**
   * @return int
   */
  public function get(): int {
    return 0xFF & $this->value;
  }

  /**
   * @return \Mqtt\Protocol\Binary\Data\Bit
   */
  public function bits(): \Mqtt\Protocol\Binary\Data\Bit {
    return $this->bit->uint($this);
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return \chr($this->get());
  }

  public function __clone() {
    $this->value = 0;
    $this->bit = clone $this->bit;
    $this->bit->uint($this);
  }

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @return void
   */
  public function decode(\Mqtt\Protocol\Binary\IBuffer $buffer): void {
    $this->set($buffer->getChar());
  }

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @return void
   */
  public function encode(\Mqtt\Protocol\Binary\IBuffer $buffer): void {
    $buffer->append((string) $this);
  }

}
