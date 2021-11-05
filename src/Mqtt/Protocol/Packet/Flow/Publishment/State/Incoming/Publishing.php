<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming;

class Publishing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $this->flowContext->setIncomingPacket($packet);

    if ($packet->qos === \Mqtt\Entity\IQoS::AT_MOST_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_AT_MOST_ONCE);
    } elseif ($packet->qos === \Mqtt\Entity\IQoS::AT_LEAST_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_AT_LEAST_ONCE);
    } elseif ($packet->qos === \Mqtt\Entity\IQoS::EXACTLY_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_PUBLISHING_EXACTLY_ONCE);
    }
  }

}
