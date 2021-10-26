<?php

namespace Mqtt\Session\State;

class Disconnecting implements \Mqtt\Session\State\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function publish() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function unsubscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolDisconnect(): void {
    $this->stateChanger->setState(\Mqtt\Session\State\IState::DISCONNECTED);
  }

}
