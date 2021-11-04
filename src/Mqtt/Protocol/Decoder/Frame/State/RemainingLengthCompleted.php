<?php

namespace Mqtt\Protocol\Decoder\Frame\State;

class RemainingLengthCompleted  implements \Mqtt\Protocol\Decoder\Frame\State\IState {

  use \Mqtt\Protocol\Decoder\Frame\State\TState;

  public function onEnter() {
    if ($this->context->remainingLengthHeader->get() > 0) {
      $this->action->setState(\Mqtt\Protocol\Decoder\Frame\State\IState::PAYLOAD_PROCESSING);
    } else {
      $this->action->setState(\Mqtt\Protocol\Decoder\Frame\State\IState::FRAME_COMPLETED);
    }
  }

  public function input(string $char) {}

}
