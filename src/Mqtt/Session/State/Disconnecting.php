<?php declare(ticks = 1);

namespace Mqtt\Session\State;

class Disconnecting implements \Mqtt\Session\State\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  public function onStateEnter(): void {
    $disconnectPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::DISCONNECT);
    $this->context->getProtocol()->writePacket($disconnectPacket);
    $this->context->getProtocol()->disconnect();
  }

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function publish() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function unsubscribe() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolDisconnect(): void {
    $this->stateChanger->setState(\Mqtt\Session\State\IState::DISCONNECTED);
  }

}
