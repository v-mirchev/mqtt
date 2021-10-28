<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\AtMostOnce;

class Publishing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @return void
   */
  public function onStateEnter(): void {
    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_NOTIFY);
  }

}
