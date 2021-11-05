<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce;

class Republish implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @return void
   */
  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getOutgoingPacket();
    $packet->dup = true;

    $this->context->getProtocol()->writePacket($packet);

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_RECEIVED_WAITING);
  }

}
