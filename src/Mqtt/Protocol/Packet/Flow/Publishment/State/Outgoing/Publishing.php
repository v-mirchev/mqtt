<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing;

class Publishing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $this->flowContext->setOutgoingPacket($packet);

    if ($packet->qos === \Mqtt\Entity\IQoS::AT_MOST_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_PUBLISHING_AT_MOST_ONCE);
    } elseif ($packet->qos === \Mqtt\Entity\IQoS::AT_LEAST_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_PUBLISHING_AT_LEAST_ONCE);
    } elseif ($packet->qos === \Mqtt\Entity\IQoS::EXACTLY_ONCE ) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_PUBLISHING_EXACTLY_ONCE);
    }
  }

}
