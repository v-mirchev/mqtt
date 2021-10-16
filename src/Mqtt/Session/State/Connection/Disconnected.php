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

    $this->stateChanger->setState(\Mqtt\Session\State\ISessionState::CONNECTING);
  }

}
