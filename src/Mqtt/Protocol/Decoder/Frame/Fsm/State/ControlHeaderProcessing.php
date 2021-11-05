<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame\Fsm\State;

class ControlHeaderProcessing implements \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState {

  use \Mqtt\Protocol\Decoder\Frame\Fsm\State\TState;

  public function onEnter() {
    $this->nextState = $this;
    $this->context->controlHeaderReceiver->rewind();
  }

  public function input(string $char) {
    if (!$this->context->controlHeaderReceiver->isCompleted()) {
      $this->context->controlHeaderReceiver->input($char);
    }

    if ($this->context->controlHeaderReceiver->isCompleted()) {
      $this->action->setState(\Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::REMAINING_LENGTH_PROCESSING);
    }
  }

}
