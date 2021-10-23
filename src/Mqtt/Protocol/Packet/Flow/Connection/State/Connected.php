<?php declare(ticks = 1);

namespace Mqtt\Protocol\Packet\Flow\Connection\State;

class Connected implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function start() : void {
    throw new \Exception('Already started');
  }

  public function stop() : void {
    $this->context->getSessionStateChanger()->setState(\Mqtt\Session\State\IState::DISCONNECTING);
    $disconnectPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::DISCONNECT);
    $this->context->getProtocol()->writePacket($disconnectPacket);
    $this->context->getProtocol()->disconnect();
  }

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::CONNACK)) {
      throw new \Exception('CONNACK packet not expected');
    }
  }

  public function onStateEnter(): void {
    $this->context->getSessionStateChanger()->setState(\Mqtt\Session\State\IState::STARTED);
  }

}
