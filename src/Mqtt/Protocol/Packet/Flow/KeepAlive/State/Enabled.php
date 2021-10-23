<?php

namespace Mqtt\Protocol\Packet\Flow\KeepAlive\State;

class Enabled implements \Mqtt\Protocol\Packet\Flow\IState {
  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;


  public function onStateEnter() : void {
    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_PING_WAIT);
  }
}
