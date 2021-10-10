<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class PingWaiting implements \Mqtt\Session\State\ISessionState, \Mqtt\ITimeoutHandler {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\Connection\TState;

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

  public function onProtocolConnect(): void {}

  public function onProtocolDisconnect(): void {}

  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {}

  public function onStateEnter(): void {
    $this->timeout->setInterval(ceil($this->context->getSessionConfiguration()->keepAliveInterval / 2));
    $this->timeout->subscribe($this);
    $this->timeout->start();
  }

  public function onTick(): void {
    $this->timeout->tick();
  }

  public function onTimeout(): void {
    $pingPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacket::PINGREQ);
    $this->context->getProtocol()->writePacket($pingPacket);

    $this->stateChanger->setState(\Mqtt\Session\State\ISessionState::PONG_WAIT);
  }

}
