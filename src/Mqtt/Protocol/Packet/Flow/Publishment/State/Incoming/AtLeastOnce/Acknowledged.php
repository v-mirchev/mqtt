<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\AtLeastOnce;

class Acknowledged implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_NOTIFY);
  }

}
