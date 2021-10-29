<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce;

class Completed implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getOutgoingPacket();

    $this->context->getIdProvider()->free($packet->id);
    $this->context->getPublishmentOutgoingFlowQueue()->remove($packet->id);
  }

}
