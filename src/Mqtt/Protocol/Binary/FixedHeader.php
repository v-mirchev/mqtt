<?php

namespace Mqtt\Protocol\Binary;

class FixedHeader implements IFixedHeader {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $byte;

  /**
   * @var \Mqtt\Protocol\Binary\IControlHeader
   */
  protected $controlHeader;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8[]
   */
  protected $packetLengthBytes;

  /**
   * @var int
   */
  protected $remainingLength;

  /**
   * @param \Mqtt\Protocol\Binary\ControlHeader $controlHeader
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $byte
   */
  public function __construct(
    \Mqtt\Protocol\Binary\ControlHeader $controlHeader,
    \Mqtt\Protocol\Binary\Data\Uint8 $byte
  ) {
    $this->controlHeader = clone $controlHeader;
    $this->byte = clone $byte;
    $this->packetLengthBytes = [];
  }

  /**
   * @return int
   */
  public function getPacketType() : int {
    return $this->controlHeader->getPacketType();
  }

  /**
   * @param type $type
   * @return $this
   */
  public function setPacketType(int $type) : IFixedHeader {
    $this->controlHeader->setPacketType($type);
    return $this;
  }

  /**
   * @param type $value
   * @return $this
   */
  public function setReserved(int $value) : IFixedHeader {
    $this->controlHeader->setReserved($value);
    return $this;
  }

  /**
   * @return int
   */
  public function getQoS() : int {
    return $this->controlHeader->getQoS();
  }

  /**
   * @param int $qos
   * @return $this
   */
  public function setQoS(int $qos) : IFixedHeader {
    $this->controlHeader->setQoS($qos);
    return $this;
  }

  /**
   * @return bool
   */
  public function isDup() : bool {
    return $this->controlHeader->isDup();
  }

  /**
   * @param bool $dup
   * @return $this
   */
  public function setAsDup(bool $dup = true) : IFixedHeader {
    $this->controlHeader->setAsDup($dup);
    return $this;
  }

  /**
   * @return type
   */
  public function isRetain() : bool {
    return $this->controlHeader->isRetain();
  }

  /**
   * @param bool $retain
   * @return $this
   */
  public function setAsRetain(bool $retain = true) : IFixedHeader {
    $this->controlHeader->setAsRetain($retain);
    return $this;
  }

  /**
   * @param int $remainingLength
   * @return $this
   */
  public function setRemainingLength(int $remainingLength) : IFixedHeader {
    $this->remainingLength = $remainingLength;
    $this->packetLengthBytes = [];

    do {
      $lengthUint8 = (clone $this->byte)->set($remainingLength);
      $packetLengthByte = (clone $this->byte)->set($lengthUint8->bits()->getSub(0, 6));
      $remainingLength >>= 7;
      if ($remainingLength > 0) {
        $packetLengthByte->bits()->setBit(7, true);
      }
      $this->packetLengthBytes[] = $packetLengthByte;
    } while ($remainingLength > 0);

    return $this;
  }

  /**
   * @param \Iterator $stream
   * @return \Mqtt\Protocol\Binary\IFixedHeader
   */
  public function decode(\Iterator $stream) : IFixedHeader {
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
   * @return int
   */
  public function getRemainingLength(): int {
    return $this->remainingLength;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return implode('', $this->encode());
  }

  public function __clone() {
    $this->controlHeader = clone $this->controlHeader;
    $this->packetLengthBytes = [ clone $this->byte ];
    $this->remainingLength = 0;
  }

  /**
   * @return \Mqtt\Protocol\Binary\Data\Uint8[]
   */
  public function encode(): array {
    return array_merge([$this->controlHeader], $this->packetLengthBytes);
  }

}
