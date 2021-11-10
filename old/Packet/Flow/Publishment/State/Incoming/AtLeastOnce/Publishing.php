<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\AtLeastOnce;

class Publishing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @return void
   */
  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getIncomingPacket();

    /* @var $pubAckPacket \Mqtt\Protocol\Packet\Type\PubAck */
    $pubAckPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacketType::PUBACK);
    $pubAckPacket->id = $packet->id;

    $this->context->getProtocol()->writePacket($pubAckPacket);

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_INCOMING_ACKNOWLEDGED);
  }

}
