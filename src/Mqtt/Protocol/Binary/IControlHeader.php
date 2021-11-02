<?php

namespace Mqtt\Protocol\Binary;

interface IControlHeader {

  /**
   * @return int
   */
  public function set(Mqtt\Protocol\Binary\Data\Uint8 $uint8) : \Mqtt\Protocol\Binary\IControlHeader;

  /**
   * @return int
   */
  public function getPacketType() : int;

  /**
   * @param type $type
   * @return $this
   */
  public function setPacketType(int $type) : \Mqtt\Protocol\Binary\IControlHeader;

  /**
   * @param type $value
   * @return $this
   */
  public function setReserved(int $value) : \Mqtt\Protocol\Binary\IControlHeader;

  /**
   * @return int
   */
  public function getQoS() : int;

  /**
   * @param int $qos
   * @return $this
   */
  public function setQoS(int $qos) : \Mqtt\Protocol\Binary\IControlHeader;

  /**
   * @return bool
   */
  public function isDup() : bool;

  /**
   * @param bool $dup
   * @return $this
   */
  public function setAsDup(bool $dup = true) : \Mqtt\Protocol\Binary\IControlHeader;

  /**
   * @return type
   */
  public function isRetain() : bool;

  /**
   * @param bool $retain
   * @return $this
   */
  public function setAsRetain(bool $retain = true) : \Mqtt\Protocol\Binary\IControlHeader;

  /**
   * @param \Iterator $stream
   */
  public function decode(\Iterator $stream) : \Mqtt\Protocol\Binary\IControlHeader;

  /**
   * @return \Mqtt\Protocol\Binary\Data\Uint8
   */
  public function encode() : array;

  /**
   * @return string
   */
  public function __toString() : string;

}
