<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce;

class Publishing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @return void
   */
  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getOutgoingPacket();
    $packet->id = $this->context->getIdProvider()->get();

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_RECEIVED_WAITING);
  }

}
