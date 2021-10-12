<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class Disconnected implements \Mqtt\Session\State\ISessionState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\Connection\TState;

  public function start() : void {
    $this->context->getProtocol()->connect();
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

  public function onProtocolConnect(): void {
    $sessionConfiguration = $this->context->getSessionConfiguration();
    $connectPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::CONNECT);
    $connectPacket->cleanSession = !$sessionConfiguration->isPersistent;
    $connectPacket->keepAliveInterval = $sessionConfiguration->keepAliveInterval;
    $connectPacket->will = $sessionConfiguration->will;
    $connectPacket->clientId = $sessionConfiguration->clientId;
    $connectPacket->username = $sessionConfiguration->authentication->username;
    $connectPacket->password = $sessionConfiguration->authentication->password;
    $this->context->getProtocol()->writePacket($connectPacket);
  }

  public function onProtocolDisconnect(): void {
  }

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if (!$packet->is(\Mqtt\Protocol\Packet\IType::CONNACK)) {
      throw new \Exception('CONNACK packet expected');
    }

    if ($packet->getReturnCode() !== 0) {
      throw new \Exception($packet->getReturnCodeMessage() .', error code ' . $packet->getReturnCode());
    }

    $this->stateChanger->setState(\Mqtt\Session\State\ISessionState::CONNECTED);
  }

  public function onTick(): void {

  }

}
