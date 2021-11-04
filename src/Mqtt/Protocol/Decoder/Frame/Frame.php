<?php

namespace Mqtt\Protocol\Decoder\Frame;

class Frame implements \Mqtt\Protocol\Decoder\Frame\IStreamDecoder {

  use \Mqtt\Protocol\Decoder\Frame\TReceiver;

  /**
   * @var \Mqtt\Protocol\Binary\Data\IBuffer
   */
  protected $buffer;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\FrameFsm
   */
  protected $fsm;

  /**
   * @param \Mqtt\Protocol\Binary\Data\IBuffer $buffer
   * @param \Mqtt\Protocol\Decoder\Frame\FrameFsm $fsm
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\IBuffer $buffer,
    \Mqtt\Protocol\Decoder\Frame\FrameFsm $fsm
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
