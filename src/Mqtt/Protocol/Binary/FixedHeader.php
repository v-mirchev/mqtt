<?php

namespace Mqtt\Protocol\Binary;

class FixedHeader {

  /**
   * @var int[]
   */
  protected $bytes;

  /**
   * @var int
   */
  protected $remainingLength;

  public function __construct() {
    $this->bytes = [ 0, 0 ];
    $this->remainingLength = 0;
  }

  /**
   * @return int
   */
  public function getPacketType() : int {
    return intval(($this->bytes[0] & 0xF0) >> 4);
  }

  /**
   * @param type $type
   * @return $this
   */
  public function setPacketType(int $type) {
    $this->bytes[0] &= 0x0F;
    $this->bytes[0] |= ($type << 4) & 0xF0;
    return $this;
  }

  /**
   * @return int
   */
  public function getQoS() : int {
    return (($this->bytes[0] & 0x06) >> 1);
  }

  /**
   * @param int $qos
   * @return $this
   */
  public function setQoS(int $qos) {
    $this->bytes[0] &= 0xF9;
    $this->bytes[0] |= ($qos << 1) & 0x06;
    return $this;
  }

  /**
   * @return bool
   */
  public function isDup() : bool {
    return ($this->bytes[0] & 0x08) > 0;
  }

  /**
   * @param bool $dup
   * @return $this
   */
  public function setAsDup(bool $dup = true) {
    if ($dup) {
      $this->bytes[0] |= 0x08;
    } else {
      $this->bytes[0] &= 0xF7;
    }
    return $this;
  }

  /**
   * @return type
   */
  public function isRetain() : bool {
    return ($this->bytes[0] & 0x01) > 0;
  }

  /**
   * @param bool $retain
   * @return $this
   */
  public function setAsRetain(bool $retain = true) {
    if ($retain) {
      $this->bytes[0] |= 0x01;
    } else {
      $this->bytes[0] &= 0xFE;
    }
    return $this;
  }

  /**
   * @param int $remainingLength
   * @return $this
   */
  public function setRemainingLength(int $remainingLength) {
    $this->remainingLength = $remainingLength;
    $this->bytes = [ $this->bytes[0] ];

    do {
      $digit = $remainingLength % 128;
      $remainingLength >>= 7;
      if ($remainingLength > 0) {
        $digit |= 0x80;
      }
      $this->bytes[] = $digit;
    } while ($remainingLength > 0);

    return $this;
  }

  /**
   * @param \Iterator $stream
   */
  public function fromStream(\Iterator $stream) {
    $this->bytes = [ 0, 0 ];
    $this->bytes[0] = ord($stream->current());

    $multiplier = 1;
    $this->remainingLength = 0;
    do {
      $stream->next();
      $digit = ord($stream->current());
      $this->remainingLength += ($digit & 127) * $multiplier;
      $multiplier *= 128;
    } while (($digit & 128) !== 0);

  }

  /**
   * @return int
   */
  public function getRemainingLength(): int {
    return $this->remainingLength;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return implode('', array_map('chr', $this->bytes));
  }

  public function __clone() {
    $this->bytes = [ 0, 0 ];
    $this->remainingLength = 0;
  }

}
