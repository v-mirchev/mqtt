<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class PingWaiting implements \Mqtt\Session\State\ISessionKeepAliveState {

  use \Mqtt\Session\State\Connection\TSession;
  use \Mqtt\Session\State\Connection\TSessionKeepAlive;

  /**
   * @var \Mqtt\Session\ISessionKeepAliveStateChanger
   */
  protected $stateChanger;

  /**
   * @var \Mqtt\Session\ISessionContext
   */
  protected $context;

  /**
   * @var \Mqtt\Session\ISessionKeepAliveContext
   */
  protected $keepAliveContext;

  public function start() : void {
  }

  public function stop() : void {
  }

  public function publish() : void {
  }

  public function subscribe() : void {
  }

  public function unsubscribe(): void {
  }

  public function onProtocolConnect(): void {
    $this->context->getTimeoutWatcher()->start();
  }

  public function onProtocolDisconnect(): void {
    $this->context->getTimeoutWatcher()->stop();
  }

  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {
    $this->keepAliveContext->getSession()->onPacketReceived($packet);
  }

  public function onTick(): void {
    $this->keepAliveContext->getTimeoutWatcher()->tick();
  }

  public function onTimeout(): void {
    $pingPacket = $this->keepAliveContext->getProtocol()->createPacket(\Mqtt\Protocol\IPacket::PINGREQ);
    $this->keepAliveContext->getProtocol()->writePacket($pingPacket);
    $this->stateChanger->setKeepAliveState(\Mqtt\Session\State\ISessionState::PONG_WAIT);
  }

}
