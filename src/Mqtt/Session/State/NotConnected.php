<?php declare(ticks = 1);

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

  public function publish() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolConnect(): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolDisconnect(): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    throw new \Exception('Not allowed in this state');
  }

}
