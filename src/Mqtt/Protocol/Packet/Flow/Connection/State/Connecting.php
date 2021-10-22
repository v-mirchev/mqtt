<?php declare(ticks = 1);

namespace Mqtt\Protocol\Packet\Flow\Connection\State;

class Connecting implements \Mqtt\Protocol\Packet\Flow\IState, \Mqtt\ITimeoutHandler {

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

  /**
   * @return void
   */
  public function onStateEnter(): void {
    $this->timeout->setInterval(60);
    $this->timeout->subscribe($this);
    $this->timeout->start();
  }

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if (!$packet->is(\Mqtt\Protocol\Packet\IType::CONNACK)) {
      throw new \Exception('CONNACK packet expected');
    }

    if ($packet->getReturnCode() !== 0) {
      throw new \Exception($packet->getReturnCodeMessage() .', error code ' . $packet->getReturnCode());
    }

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::CONNECTED);
  }

  public function onTick() : void {
    $this->timeout->tick();
  }

  public function onTimeout(): void {
    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::DISCONNECTED);
  }

  public function __destruct() {
    $this->timeout->stop();
    unset($this->timeout);
  }

}
