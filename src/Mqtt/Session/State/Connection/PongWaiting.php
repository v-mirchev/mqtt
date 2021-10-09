<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class PongWaiting implements \Mqtt\Session\State\ISessionKeepAliveState {

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
    $this->keepAliveContext->getTimeoutWatcher()->start();
  }

  public function onProtocolDisconnect(): void {
    $this->keepAliveContext->getTimeoutWatcher()->stop();
  }

  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacket::PINGRESP)) {
      $this->keepAliveContext->getTimeoutWatcher()->reset();
      $this->stateChanger->setKeepAliveState(\Mqtt\Session\State\ISessionState::PING_WAIT);
    }
  }

  public function onTick(): void {
    $this->keepAliveContext->getTimeoutWatcher()->tick();
  }

  public function onTimeout(): void {
    $this->context->getSession()->stop();
  }

}
