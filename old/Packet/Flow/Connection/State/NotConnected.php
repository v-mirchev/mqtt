<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Connection\State;

class NotConnected implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    $sessionConfiguration = $this->context->getSessionConfiguration();
    $connectPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacketType::CONNECT);
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
