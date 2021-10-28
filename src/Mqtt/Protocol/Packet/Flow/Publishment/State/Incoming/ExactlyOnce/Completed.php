<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\ExactlyOnce;

class Completed implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getIncomingPacket();

    /* @var $pubCompPacket \Mqtt\Protocol\Packet\Type\PubComp */
    $pubCompPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::PUBCOMP);
    $pubCompPacket->id = $packet->id;

    $this->context->getProtocol()->writePacket($pubCompPacket);

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_NOTIFY);
  }

}
