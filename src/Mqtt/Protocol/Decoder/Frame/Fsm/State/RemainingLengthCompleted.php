<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame\Fsm\State;

class RemainingLengthCompleted  implements \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState {

  use \Mqtt\Protocol\Decoder\Frame\Fsm\State\TState;

  public function onEnter() {
    if ($this->context->remainingLengthHeader->get() > 0) {
      $this->action->setState(\Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::PAYLOAD_PROCESSING);
    } else {
      $this->action->setState(\Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::FRAME_COMPLETED);
    }
  }

  public function input(string $char) {}

}
