<?php

namespace Mqtt\Protocol\Decoder\Frame\Fsm\State;

class FrameCompleted implements \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState {

  use \Mqtt\Protocol\Decoder\Frame\Fsm\State\TState;

  public function onEnter() {
    $this->action->complete();
    $this->action->setState(\Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::CONTROL_HEADER_PROCESSING);
  }

  public function input(string $char) {}

}
