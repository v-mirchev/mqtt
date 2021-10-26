<?php

namespace Mqtt\Protocol\Packet\Flow\Unsubscription\State;

class Unsubscribing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {
    $packet->id = $this->context->getIdProvider()->get();

    $this->flowContext->setOutgoingPacket($packet);
    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::UNSUBSCRIBE_ACK_WAITING);
  }

}
