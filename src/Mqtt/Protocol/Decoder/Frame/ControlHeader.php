<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame;

class ControlHeader implements \Mqtt\Protocol\Decoder\Frame\IStreamDecoder {

  use \Mqtt\Protocol\Decoder\Frame\TReceiver;

  const BIT_START_FLAGS = 0;
  const BIT_END_FLAGS = 3;
  const BIT_START_PACKET_TYPE = 4;
  const BIT_END_PACKET_TYPE = 7;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $controlHeader;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $uint8
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $uint8) {
    $this->controlHeader = clone $uint8;
  }

  public function __clone() {
    $this->controlHeader = clone $this->controlHeader;
  }

  /**
   * @return \Generator
   */
  public function streamDecoder() : \Generator {
    $char = yield;
    $this->controlHeader->set($char);
  }

  /**
   * @return int
   */
  public function getPacketType() : int {
    return $this->controlHeader->bits()->getSub(static::BIT_START_PACKET_TYPE, static::BIT_END_PACKET_TYPE)->get();
  }

  /**
   * @return int
   */
  public function getFlags() : \Mqtt\Protocol\Binary\Data\Uint8 {
    return $this->controlHeader->bits()->getSub(static::BIT_START_FLAGS, static::BIT_END_FLAGS);
  }

}
