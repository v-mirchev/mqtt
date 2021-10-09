<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class Connected implements \Mqtt\Session\State\ISessionState {

  use \Mqtt\Session\State\Connection\TSession;

  public function start() : void {
    throw new \Exception('Already started');
  }

  public function stop() : void {
    $disconnectPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacket::DISCONNECT);
    $this->context->getProtocol()->writePacket($disconnectPacket);
    $this->context->getProtocol()->disconnect();
  }

  public function publish() : void {
  }

  public function subscribe() : void {
  }

  public function unsubscribe(): void {
  }

  public function onProtocolConnect(): void {
  }

  public function onProtocolDisconnect(): void {
    $this->stateChanger->setState(\Mqtt\Session\State\ISessionState::DISCONNECTED);
  }

  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {
  }

  public function onTick(): void {

  }

}
