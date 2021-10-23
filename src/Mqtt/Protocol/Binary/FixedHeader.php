<?php

namespace Mqtt\Protocol\Binary;

class FixedHeader implements IFixedHeader {

  /**
   * @var \Mqtt\Protocol\Binary\Byte
   */
  protected $byte;

  /**
   * @var \Mqtt\Protocol\Binary\Byte[]
   */
  protected $bytes;

 /**
   * @var int
   */
  protected $remainingLength;

  /**
   * @param \Mqtt\Protocol\Binary\Byte $byte
   */
  public function __construct(\Mqtt\Protocol\Binary\Byte $byte) {
    $this->byte = clone $byte;
    $this->bytes = [ clone $byte ];
  }

  /**
   * @return int
   */
  public function getPacketType() : int {
    return $this->byte->getSub(IFixedHeader::BIT_TYPE_LS, IFixedHeader::BIT_TYPE_MS);
  }

  /**
   * @param type $type
   * @return $this
   */
  public function setPacketType(int $type) : IFixedHeader {
    $this->byte->setSub(IFixedHeader::BIT_TYPE_LS, IFixedHeader::BIT_TYPE_MS, $type);
    return $this;
  }

  /**
   * @param type $value
   * @return $this
   */
  public function setReserved(int $value) : IFixedHeader {
    $this->byte->setSub(IFixedHeader::BIT_RESERVED_LS, IFixedHeader::BIT_RESERVED_MS, $value);
    return $this;
  }

  /**
   * @return int
   */
  public function getQoS() : int {
    return $this->byte->getSub(IFixedHeader::BIT_QOS_LS, IFixedHeader::BIT_QOS_MS);
  }

  /**
   * @param int $qos
   * @return $this
   */
  public function setQoS(int $qos) : IFixedHeader {
    $this->byte->setSub(IFixedHeader::BIT_QOS_LS, IFixedHeader::BIT_QOS_MS, $qos);
    return $this;
  }

  /**
   * @return bool
   */
  public function isDup() : bool {
    return $this->byte->getBit(IFixedHeader::BIT_DUP);
  }

  /**
   * @param bool $dup
   * @return $this
   */
  public function setAsDup(bool $dup = true) : IFixedHeader {
    $this->byte->setBit(IFixedHeader::BIT_DUP, $dup);
    return $this;
  }

  /**
   * @return type
   */
  public function isRetain() : bool {
    return $this->byte->getBit(IFixedHeader::BIT_RETAIN);
  }

  /**
   * @param bool $retain
   * @return $this
   */
  public function setAsRetain(bool $retain = true) : IFixedHeader {
    $this->byte->setBit(IFixedHeader::BIT_RETAIN, $retain);
    return $this;
  }

  /**
   * @param int $remainingLength
   * @return $this
   */
  public function setRemainingLength(int $remainingLength) : IFixedHeader {
    $this->remainingLength = $remainingLength;
    $this->bytes = [];

    do {
      $lengthByte = (clone $this->byte)->set($remainingLength);
      $byte = (clone $this->byte)->set($lengthByte->getSub(0, 6));
      $remainingLength >>= 7;
      if ($remainingLength > 0) {
        $byte->setBit(7, true);
      }
      $this->bytes[] = $byte;
    } while ($remainingLength > 0);

    return $this;
  }

  /**
   * @param \Iterator $stream
   * @return \Mqtt\Protocol\Binary\IFixedHeader
   */
  public function decode(\Iterator $stream) : IFixedHeader {
    $this->bytes = [ clone $this->byte ];
    $this->byte->set($stream->current());

    $multiplier = 1;
    $this->remainingLength = 0;
    do {
      $stream->next();
      $byte = clone $this->byte;
      $byte->set($stream->current());
      $this->remainingLength += $byte->getSub(0, 6) * $multiplier;
      $multiplier *= 128;
    } while ($byte->getBit(7));

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
    $this->byte = clone $this->byte;
    $this->bytes = [ clone clone $this->byte ];
    $this->remainingLength = 0;
  }

  /**
   * @return \Mqtt\Protocol\Binary\Byte[]
   */
  public function encode(): array {
    return array_merge([], [$this->byte], $this->bytes);
  }

}
