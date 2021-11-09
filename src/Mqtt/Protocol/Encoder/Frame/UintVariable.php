<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Frame;

class UintVariable implements \Mqtt\Protocol\Encoder\Frame\IStreamEncoder {

  const MAX_VALUE = 268435455;

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

  public function encode(\Mqtt\Protocol\Binary\IBuffer $buffer): void {
    if ($this->value > static::MAX_VALUE) {
      throw new \Exception('Remaining length exceeds maximum value');
    }

    $remainingValue = $this->value;

    do {
      $lengthUint8 = (clone $this->uint8Prototype)->set($remainingValue);
      $packetLengthByte = (clone $this->uint8Prototype)->set($lengthUint8->bits()->getSub(0, 6));
      $remainingValue >>= 7;
      if ($remainingValue > 0) {
        $packetLengthByte->bits()->setBit(7, true);
      }
      $buffer->append((string)$packetLengthByte);
    } while ($remainingValue > 0);

  }

  public function set($value): void {
    $this->value = $value;
  }

}
