<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce;

class Received implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getOutgoingPacket();

    /* @var $pubRecPacket \Mqtt\Protocol\Packet\Type\PubRec */
    $pubRecPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::PUBREC);
    $pubRecPacket->id = $packet->id;

    $this->context->getProtocol()->writePacket($pubRecPacket);

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_RELEASE_WAITING);
  }

}