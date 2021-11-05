<?php

namespace Mqtt\Protocol\Binary;

class TypedBuffer implements \Mqtt\Protocol\Binary\ITypedBuffer {

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  protected $buffer;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $uint8;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $uint16;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Utf8String
   */
  protected $utf8string;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $uint8
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Binary\Data\Utf8String $utf8string
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint8 $uint8,
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Binary\Data\Utf8String $utf8string
  ) {
    $this->uint8 = $uint8;
    $this->uint16 = $uint16;
    $this->utf8string = $utf8string;
  }

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @return void
   */
  public function decorate(\Mqtt\Protocol\Binary\IBuffer $buffer): void {
    $this->buffer = $buffer;
  }

  /**
   * @param int $uint16
   * @return void
   */
  public function appendUint16(int $uint16): void {
    $this->uint16->set($uint16);
    $this->uint16->encode($this->buffer);
  }

  /**
   * @param int $uint8
   * @return void
   */
  public function appendUint8(int $uint8): void {
    $this->uint8->set($uint8);
    $this->uint8->encode($this->buffer);
  }

  /**
   * @param string $utf8String
   * @return void
   */
  public function appendUtf8String(string $utf8String): void {
    $this->utf8string->set($utf8String);
    $this->utf8string->encode($this->buffer);
  }

  /**
   * @return \Mqtt\Protocol\Binary\Data\Uint16
   */
  public function getUint16(): \Mqtt\Protocol\Binary\Data\Uint16 {
    $uint16 = clone $this->uint16;
    $uint16->decode($this->buffer);
    return $uint16;
  }

  /**
   * @return \Mqtt\Protocol\Binary\Data\Uint8
   */
  public function getUint8(): \Mqtt\Protocol\Binary\Data\Uint8 {
    $uint8 = clone $this->uint8;
    $uint8->decode($this->buffer);
    return $uint8;
  }

  /**
   * @return \Mqtt\Protocol\Binary\Data\Utf8String
   */
  public function getUtf8String(): \Mqtt\Protocol\Binary\Data\Utf8String {
    $utf8String = clone $this->utf8string;
    $utf8String->decode($this->buffer);
    return $utf8String;
  }

}
