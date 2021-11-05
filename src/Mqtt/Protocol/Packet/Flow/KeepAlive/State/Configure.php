<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\KeepAlive\State;

class Configure implements \Mqtt\Protocol\Packet\Flow\IState {
  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function start() : void {
    if ($this->context->getSessionConfiguration()->keepAliveInterval > 0) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_ENABLED);
    } else {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_DISABLED);
    }
  }
}
