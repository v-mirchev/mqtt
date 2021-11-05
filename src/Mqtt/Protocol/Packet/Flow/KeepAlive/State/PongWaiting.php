<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\KeepAlive\State;

class PongWaiting implements \Mqtt\Protocol\Packet\Flow\IState, \Mqtt\ITimeoutHandler {

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

  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacketType::PINGRESP)) {
      $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_PING_WAIT);
    }
  }

  public function onStateEnter(): void {
    $this->timeout->setInterval(ceil($this->context->getSessionConfiguration()->keepAliveInterval / 2));
    $this->timeout->subscribe($this);
    $this->timeout->start();
  }

  public function onTick(): void {
    $this->timeout->tick();
  }

  public function onTimeout(): void {
    $this->context->getSessionStateChanger()->setState(\Mqtt\Session\State\IState::DISCONNECTING);
  }

  public function __destruct() {
    $this->timeout->stop();
    unset($this->timeout);
  }

}
