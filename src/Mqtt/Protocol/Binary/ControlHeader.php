<?php

namespace Mqtt\Protocol\Binary;

class ControlHeader implements IControlHeader {

  const BIT_RETAIN = 0;
  const BIT_QOS_LS = 1;
  const BIT_QOS_MS = 2;
  const BIT_DUP = 3;
  const BIT_TYPE_LS = 4;
  const BIT_TYPE_MS = 7;
  const BIT_RESERVED_LS = 0;
  const BIT_RESERVED_MS = 3;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $controlHeader;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $uint8
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $uint8) {
    $this->controlHeader = $uint8;
  }

  /**
   * @param string|int $value
   * @return \Mqtt\Protocol\Binary\IControlHeader
   */
  public function set($value) : \Mqtt\Protocol\Binary\IControlHeader {
    $this->controlHeader->set($value);
    return $this;
  }

  /**
   * @return int
   */
  public function getPacketType() : int {
    return $this->controlHeader->bits()->getSub(static::BIT_TYPE_LS, static::BIT_TYPE_MS)->get();
  }

  /**
   * @param type $type
   * @return $this
   */
  public function setPacketType(int $type) : \Mqtt\Protocol\Binary\IControlHeader {
    $this->controlHeader->bits()->setSub(static::BIT_TYPE_LS, static::BIT_TYPE_MS, $type);
    return $this;
  }

  /**
   * @param type $value
   * @return $this
   */
  public function setReserved(int $value) : \Mqtt\Protocol\Binary\IControlHeader {
    $this->controlHeader->bits()->setSub(static::BIT_RESERVED_LS, static::BIT_RESERVED_MS, $value);
    return $this;
  }

  /**
   * @return int
   */
  public function getQoS() : int {
    return $this->controlHeader->bits()->getSub(static::BIT_QOS_LS, static::BIT_QOS_MS)->get();
  }

  /**
   * @param int $qos
   * @return $this
   */
  public function setQoS(int $qos) : \Mqtt\Protocol\Binary\IControlHeader {
    $this->controlHeader->bits()->setSub(static::BIT_QOS_LS, static::BIT_QOS_MS, $qos);
    return $this;
  }

  /**
   * @return bool
   */
  public function isDup() : bool {
    return $this->controlHeader->bits()->getBit(static::BIT_DUP);
  }

  /**
   * @param bool $dup
   * @return $this
   */
  public function setAsDup(bool $dup = true) : \Mqtt\Protocol\Binary\IControlHeader {
    $this->controlHeader->bits()->setBit(static::BIT_DUP, $dup);
    return $this;
  }

  /**
   * @return type
   */
  public function isRetain() : bool {
    return $this->controlHeader->bits()->getBit(static::BIT_RETAIN);
  }

  /**
   * @param bool $retain
   * @return $this
   */
  public function setAsRetain(bool $retain = true) : \Mqtt\Protocol\Binary\IControlHeader {
    $this->controlHeader->bits()->setBit(static::BIT_RETAIN, $retain);
    return $this;
  }

  /**
   * @param \Iterator $stream
   * @return \Mqtt\Protocol\Binary\\Mqtt\Protocol\Binary\IControlHeader
   */
  public function decode(\Iterator $stream) : \Mqtt\Protocol\Binary\IControlHeader {
    $this->controlHeader->set($stream->current());

    $multiplier = 1;
    $this->remainingLength = 0;
    do {
      $stream->next();
      $packetLengthByte = clone $this->byte;
      $packetLengthByte->set($stream->current());
      $this->remainingLength += $packetLengthByte->bits()->getSub(0, 6)->get() * $multiplier;
      $multiplier *= 128;
    } while ($packetLengthByte->bits()->getBit(7));

    return $this;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return (string)$this->controlHeader;
  }

  public function __clone() {
    $this->controlHeader = clone $this->controlHeader;
  }

  /**
   * @return \Mqtt\Protocol\Binary\Data\Uint8[]
   */
  public function encode(): array {
    return [$this->controlHeader];
  }

}
