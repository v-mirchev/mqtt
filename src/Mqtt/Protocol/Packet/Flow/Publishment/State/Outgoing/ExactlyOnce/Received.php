<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce;

class Received implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getOutgoingPacket();

    /* @var $pubRelPacket \Mqtt\Protocol\Packet\Type\PubRel */
    $pubRelPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::PUBREL);
    $pubRelPacket->id = $packet->id;

    $this->context->getProtocol()->writePacket($pubRelPacket);

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_COMPLETED_WAITING);
  }

}
