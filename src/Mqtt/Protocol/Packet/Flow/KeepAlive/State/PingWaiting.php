<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\KeepAlive\State;

class PingWaiting implements \Mqtt\Protocol\Packet\Flow\IState, \Mqtt\ITimeoutHandler {

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

  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacketType::PINGRESP)) {
      throw new \Exception('PONG packet not expected');
    }
    $this->timeout->reset();
  }

  public function onStateEnter(): void {
    $this->timeout->setInterval(ceil($this->context->getSessionConfiguration()->keepAliveInterval));
    $this->timeout->subscribe($this);
    $this->timeout->start();
  }

  public function onTick(): void {
    $this->timeout->tick();
  }

  public function onTimeout(): void {
    $pingPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacketType::PINGREQ);
    $this->context->getProtocol()->writePacket($pingPacket);

    $this->stateChanger->setState(\Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_PONG_WAIT);
  }

  public function __destruct() {
    $this->timeout->stop();
    unset($this->timeout);
  }

}
