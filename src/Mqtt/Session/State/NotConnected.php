<?php

namespace Mqtt\Session\State;

class NotConnected implements \Mqtt\Session\State\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  public function start() : void {
    $this->stateChanger->setState(\Mqtt\Session\State\IState::CONNECTING);
  }

  public function stop() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function unsubscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function publish(\Mqtt\Entity\Message $message) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolConnect(): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolDisconnect(): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onTick(): void {
    throw new \Exception('Not allowed in this state');
  }

}
