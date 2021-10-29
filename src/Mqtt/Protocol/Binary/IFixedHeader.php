<?php

namespace Mqtt\Protocol\Binary;

interface IFixedHeader {

  const MAX_REMAINING_LENGTH = 268435455;

  const BIT_RETAIN = 0;
  const BIT_QOS_LS = 1;
  const BIT_QOS_MS = 2;
  const BIT_DUP = 3;
  const BIT_TYPE_LS = 4;
  const BIT_TYPE_MS = 7;
  const BIT_RESERVED_LS = 0;
  const BIT_RESERVED_MS = 3;

  /**
  /**
   * @return int
   */
  public function getPacketType() : int;

  /**
   * @param type $type
   * @return $this
   */
  public function setPacketType(int $type) : IFixedHeader;

  /**
   * @param type $value
   * @return $this
   */
  public function setReserved(int $value) : IFixedHeader;

  /**
   * @return int
   */
  public function getQoS() : int;

  /**
   * @param int $qos
   * @return $this
   */
  public function setQoS(int $qos) : IFixedHeader;

  /**
   * @return bool
   */
  public function isDup() : bool;

  /**
   * @param bool $dup
   * @return $this
   */
  public function setAsDup(bool $dup = true) : IFixedHeader;

  /**
   * @return type
   */
  public function isRetain() : bool;

  /**
   * @param bool $retain
   * @return $this
   */
  public function setAsRetain(bool $retain = true) : IFixedHeader;

  /**
   * @param int $remainingLength
   * @return $this
   */
  public function setRemainingLength(int $remainingLength) : IFixedHeader;

  /**
   * @param \Iterator $stream
   */
  public function decode(\Iterator $stream) : IFixedHeader;

  /**
   * @return \Mqtt\Protocol\Binary\Data\Byte
   */
  public function encode() : array;

  /**
   * @return int
   */
  public function getRemainingLength(): int;

  /**
   * @return string
   */
  public function __toString() : string;

}
