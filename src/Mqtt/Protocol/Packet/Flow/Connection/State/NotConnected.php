<?php

namespace Mqtt\Protocol\Packet\Flow\Connection\State;

class NotConnected implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function start() : void {
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

  }

  public function onStateEnter(): void {
    $sessionConfiguration = $this->context->getSessionConfiguration();
    $connectPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::CONNECT);
    $connectPacket->cleanSession = !$sessionConfiguration->isPersistent;
    $connectPacket->keepAliveInterval = $sessionConfiguration->keepAliveInterval;
    $connectPacket->will = $sessionConfiguration->will;
    $connectPacket->clientId = $sessionConfiguration->clientId;
    $connectPacket->username = $sessionConfiguration->authentication->username;
    $connectPacket->password = $sessionConfiguration->authentication->password;
    $this->context->getProtocol()->writePacket($connectPacket);

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::CONNECTING);
  }

}
