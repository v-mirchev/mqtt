<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\AtLeastOnce;

class Acknowledged implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getOutgoingPacket();

    $this->context->getIdProvider()->free($packet->id);
    $this->context->getPublishmentOutgoingFlowQueue()->remove($packet->id);

    $packet->message->handler->onMessageAcknowledged($packet->message);
  }

}
