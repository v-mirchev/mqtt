<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\AtLeastOnce;

class Acknowledged implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getOutgoingPacket();

    if ($packet->id) {
      $this->context->getIdProvider()->free($packet->id);
      $this->context->getPublishmentOutgoingFlowQueue()->remove($packet->id);
    }
  }

}
