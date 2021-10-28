<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing;

class Publishing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $this->flowContext->setOutgoingPacket($packet);

    if ($packet->qos === \Mqtt\Entity\IQoS::AT_MOST_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_AT_MOST_ONCE);
    } elseif ($packet->qos === \Mqtt\Entity\IQoS::AT_LEAST_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_AT_LEAST_ONCE);
    } elseif ($packet->qos === \Mqtt\Entity\IQoS::EXACTLY_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_EXACTLY_ONCE);
    }
  }

}
