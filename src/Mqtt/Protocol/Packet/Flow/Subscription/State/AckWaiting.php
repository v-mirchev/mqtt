<?php

namespace Mqtt\Protocol\Packet\Flow\Subscription\State;

class AckWaiting implements \Mqtt\Protocol\Packet\Flow\IState, \Mqtt\ITimeoutHandler {

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

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::SUBACK) && $packet->id === $this->flowContext->getOutgoingPacket()->id) {
      $this->flowContext->setIncomingPacket($packet);
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::SUBSCRIBE_ACKNOWLEDGED);
    }
  }

  public function onStateEnter(): void {
    $this->timeout->setInterval($this->context->getSessionConfiguration()->connectAcknowledgeTimeout);
    $this->timeout->subscribe($this);
    $this->timeout->start();
  }

  public function onTick(): void {
    $this->timeout->tick();
  }

  public function onTimeout(): void {
    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::SUBSCRIBE_UNACKNOWLEDGED);
  }

  public function __destruct() {
    $this->timeout->stop();
    unset($this->timeout);
  }

}
