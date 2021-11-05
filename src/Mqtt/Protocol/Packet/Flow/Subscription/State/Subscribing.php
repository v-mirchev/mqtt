<?php

namespace Mqtt\Protocol\Packet\Flow\Subscription\State;

class Subscribing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    $packet->id = $this->context->getIdProvider()->get();

    $this->flowContext->setOutgoingPacket($packet);
    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::SUBSCRIBE_ACK_WAITING);
  }

}
