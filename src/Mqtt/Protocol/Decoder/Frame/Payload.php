<?php

namespace Mqtt\Protocol\Decoder\Frame;

class Payload implements \Mqtt\Protocol\Decoder\Frame\IStreamDecoder {

  use \Mqtt\Protocol\Decoder\Frame\TReceiver;

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  protected $buffer;

  /**
   * @var int
   */
  protected $length;

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   */
  public function __construct(\Mqtt\Protocol\Binary\IBuffer $buffer) {
    $this->buffer = clone $buffer;
    $this->length = 0;
  }

  public function __clone() {
    $this->buffer = clone $this->buffer;
    $this->length = 0;
  }

  /**
   * @param int $length
   */
  public function setLength(int $length) : void {
    $this->length = $length;
  }

  /**
   * @return \Mqtt\Protocol\Binary\IBuffer
   */
  public function get(): \Mqtt\Protocol\Binary\IBuffer {
    return $this->buffer->get();
  }

  /**
   * @return void
   */
  public function streamDecoder(): \Generator {
    $this->buffer->reset();
    while ($this->buffer->length() < $this->length) {
      $char = yield;
      $this->buffer->append($char);
    }
  }

}
