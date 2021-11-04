<?php

namespace Mqtt\Protocol\Decoder\Frame\State;

class ControlHeaderProcessing implements \Mqtt\Protocol\Decoder\Frame\State\IState {

  use \Mqtt\Protocol\Decoder\Frame\State\TState;

  public function onEnter() {
    $this->nextState = $this;
    $this->context->controlHeaderReceiver->rewind();
  }

  public function input(string $char) {
    if (!$this->context->controlHeaderReceiver->isCompleted()) {
      $this->context->controlHeaderReceiver->input($char);
    }

    if ($this->context->controlHeaderReceiver->isCompleted()) {
      $this->action->setState(\Mqtt\Protocol\Decoder\Frame\State\IState::REMAINING_LENGTH_PROCESSING);
    }
  }

}
