<?php

namespace Mqtt\Protocol\Decoder\Frame;

class Decoder implements \Mqtt\Protocol\Decoder\Frame\IDecoder {

  use \Mqtt\Protocol\Decoder\Frame\TReceiver;

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  protected $buffer;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Fsm\Fsm
   */
  protected $fsm;

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @param \Mqtt\Protocol\Decoder\Frame\Fsm $fsm
   */
  public function __construct(
    \Mqtt\Protocol\Binary\IBuffer $buffer,
    \Mqtt\Protocol\Decoder\Frame\Fsm\Fsm $fsm
  ) {
    $this->buffer = clone $buffer;
    $this->fsm = clone $fsm;

    $this->onFrameCompleted = function (\Mqtt\Protocol\Entity\Frame $frame) {};
  }

  public function __clone() {
    $this->buffer = clone $this->buffer;
    $this->fsm = clone $this->fsm;
    $this->onFrameCompleted = function (\Mqtt\Protocol\Entity\Frame $frame) {};
  }

  /**
   * @param string $chars
   */
  public function streamDecoder() : \Generator {
    $this->fsm->start();

    while (true) {
      try {
        $chars = yield;
      } catch (\Exception $stop) {
        break;
      }
      $this->buffer->append((string)$chars);
      foreach ($this->buffer as $char) {
        $this->fsm->input($char);
      }
    }
  }

  /**
   * @param callable $onFrameCompleted
   */
  public function onCompleted(callable $onFrameCompleted) : void {
    $this->fsm->onCompleted($onFrameCompleted);
  }

}
