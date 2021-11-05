<?php

namespace Mqtt\Protocol\Decoder\Frame\Fsm\State;

class PayloadProcessing implements \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState {

  use \Mqtt\Protocol\Decoder\Frame\Fsm\State\TState;

  public function onEnter() {
    $this->context->payloadReceiver->rewind();
    $this->context->payload->setLength($this->context->remainingLengthHeader->get());
  }

  public function input(string $char) {
    if (!$this->context->payloadReceiver->isCompleted()) {
      $this->context->payloadReceiver->input($char);
    }

    if ($this->context->payloadReceiver->isCompleted()) {
      $this->action->setState(\Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::FRAME_COMPLETED);
    }
  }

}
