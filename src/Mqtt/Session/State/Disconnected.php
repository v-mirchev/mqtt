<?php

namespace Mqtt\Session\State;

class Disconnected implements \Mqtt\Session\State\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  public function start() : void {
    $this->context->getProtocol()->connect();
  }

  public function stop() : void {
    throw new \Exception('Not allowed in this state');
  }

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function publish(\Mqtt\Entity\Message $message) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function unsubscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolDisconnect(): void {
    $this->client->onDisconnect();
    $this->stateChanger->setState(\Mqtt\Session\State\IState::NOT_CONNECTED);
  }

}
