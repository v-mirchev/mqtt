<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\ExactlyOnce;

class ReceivedWaiting implements \Mqtt\Protocol\Packet\Flow\IState, \Mqtt\ITimeoutHandler {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @var \Mqtt\Timeout
   */
  protected $timeout;

  /**
   * @param \Mqtt\Timeout $timeout
   */
  public function __construct(\Mqtt\Timeout $timeout) {
    $this->timeout = clone $timeout;
  }

  public function onStateEnter(): void {
    $this->timeout = clone $this->timeout;
    $this->timeout->setInterval($this->context->getSessionConfiguration()->publishReceiveTimeout);
    $this->timeout->subscribe($this);
    $this->timeout->start();
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacketType::PUBREC) && $packet->id === $this->flowContext->getOutgoingPacket()->id) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_RECEIVED);
    }
  }

  public function onTick(): void {
    $this->timeout->tick();
  }

  public function onTimeout(): void {
    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_RECEIVED_REPUBLISH);
  }

  public function __destruct() {
    $this->timeout->stop();
    unset($this->timeout);
  }

}
