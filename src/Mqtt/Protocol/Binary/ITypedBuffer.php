<?php

namespace Mqtt\Protocol\Binary;

interface ITypedBuffer {

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @return void
   */
  public function decorate(\Mqtt\Protocol\Binary\IBuffer $buffer) : void;

  /**
   * @return \Mqtt\Protocol\Binary\Data\Uint8
   */
  public function getUint8(): \Mqtt\Protocol\Binary\Data\Uint8;

  /**
   * @return \Mqtt\Protocol\Binary\Data\Uint16
   */
  public function getUint16(): \Mqtt\Protocol\Binary\Data\Uint16;

  /**
   * @return \Mqtt\Protocol\Binary\Data\Utf8String
   */
  public function getUtf8String(): \Mqtt\Protocol\Binary\Data\Utf8String;

  /**
   * @param int $uint8
   */
  public function appendUint8(int $uint8): void;

  /**
   * @param int $uint16
   */
  public function appendUint16(int $uint16): void;

  /**
   * @param string $utf8String
   */
  public function appendUtf8String(string $utf8String): void;

}
