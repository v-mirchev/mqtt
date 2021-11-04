<?php

namespace Mqtt\Protocol\Decoder\Frame\State;

class RemainingLengthProcessing  implements \Mqtt\Protocol\Decoder\Frame\State\IState {

  use \Mqtt\Protocol\Decoder\Frame\State\TState;

  public function onEnter() {
    $this->context->remainingLengthReceiver->rewind();
  }

  public function input(string $char) {
    if (!$this->context->remainingLengthReceiver->isCompleted()) {
      $this->context->remainingLengthReceiver->input($char);
    }

    if ($this->context->remainingLengthReceiver->isCompleted()) {
      $this->action->setState(\Mqtt\Protocol\Decoder\Frame\State\IState::REMAINING_LENGTH_COMPLETED);
    }
  }

}
