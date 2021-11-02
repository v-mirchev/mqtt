<?php

namespace Mqtt\Protocol\Decoder\Frame;

class UintVariable implements \Mqtt\Protocol\Decoder\Frame\IStreamDecoder {

  const BIT_VALUE_START = 0;
  const BIT_VALUE_END = 6;
  const BIT_CONTINUOUS = 7;

  const MAX_MULTIPLIER = 128 * 128 * 128;

  /**
   * @var int
   */
  protected $value;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $uint8Prototype;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Bit $uint8Prototype
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $uint8Prototype) {
    $this->uint8Prototype = clone $uint8Prototype;
    $this->value = 0;
  }

  public function __clone() {
    $this->uint8Prototype = clone $this->uint8Prototype;
    $this->value = 0;
  }

  /**
   * @return int
   */
  public function get(): int {
    return $this->value;
  }

  /**
   * @return \Generator
   */
  public function receiver(): \Generator {
    $multiplier = 1;
    $this->value = 0;

    do {
      $byte = yield;

      $packetLengthUint8 = clone $this->uint8Prototype;
      $packetLengthUint8->set($byte);

      $this->value += $packetLengthUint8->bits()->
        getSub(static::BIT_VALUE_START, static::BIT_VALUE_END)->get() * $multiplier;

      if ($multiplier > static::MAX_MULTIPLIER) {
        throw \Exception('Malformed variable integer');
      }

      $multiplier *= 128;
    } while ($packetLengthUint8->bits()->getBit(static::BIT_CONTINUOUS));

  }

}
