<?php

namespace Mqtt\Protocol\Binary\Data;

class UintVariable implements \Mqtt\Protocol\Binary\Data\ICodec {

  const BIT_VALUE_START = 0;
  const BIT_VALUE_END = 6;
  const BIT_CONTINUOUS = 7;

  /**
   * @var string
   */
  protected $value;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8[]
   */
  protected $lengthBytes;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $uint8Prototype;

  /**
   * @var int
   */
  protected $length;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Bit $uint8Prototype
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $uint8Prototype) {
    $this->uint8Prototype = clone $uint8Prototype;
    $this->lengthBytes = [clone $this->uint8Prototype];
    $this->value = 0;
  }

  /**
   * @param string $value
   * @return $this
   */
  public function set(int $value) : \Mqtt\Protocol\Binary\Data\UintVariable {
    $this->value = $value;
    return $this;
  }

  /**
   * @return string
   */
  public function get(): string {
    return $this->value;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    $value = $this->value;
    $this->packetLengthBytes = [];

    do {
      $lengthUint8 = (clone $this->byte)->set($value);
      $packetLengthUint8 = (clone $this->byte)->
        set($lengthUint8->bits()->getSub(static::BIT_VALUE_START, static::BIT_VALUE_END));

      $value >>= static::BIT_CONTINUOUS;
      if ($value > 0) {
        $packetLengthUint8->bits()->setBit(static::BIT_CONTINUOUS, true);
      }
      $this->lengthBytes[] = $packetLengthUint8;
    } while ($value > 0);
  }

  public function __clone() {
    $this->uint8Prototype = clone $this->uint8Prototype;
    $this->lengthBytes = [clone $this->uint8Prototype];
    $this->value = 0;
  }

  /**
   * @param \Mqtt\Protocol\Binary\Data\IBuffer $buffer
   * @return void
   */
  public function decode(\Mqtt\Protocol\Binary\Data\IBuffer $buffer): void {
    $multiplier = 1;
    $this->value = 0;
    do {
      $packetLengthUint8 = clone $this->uint8Prototype;
      $packetLengthUint8->set($buffer->getByte());
      $this->value += $packetLengthUint8->bits()->
        getSub(static::BIT_VALUE_START, static::BIT_VALUE_END)->get() * $multiplier;
      if ($multiplier > 128 * 128 * 128) {
        throw \Exception('Malformed Variable Byte Integer');
      }
      $multiplier *= 128;
    } while ($packetLengthUint8->bits()->getBit(static::BIT_CONTINUOUS));
  }

  /**
   * @param \Mqtt\Protocol\Binary\Data\IBuffer $buffer
   * @return void
   */
  public function encode(\Mqtt\Protocol\Binary\Data\IBuffer $buffer): void {
    $buffer->append((string) $this);
  }
}
