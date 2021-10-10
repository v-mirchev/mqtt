<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class Connected implements \Mqtt\Session\State\ISessionState {

  use \Mqtt\Session\State\Connection\TSession;

  /**
   * @var \Mqtt\Session\KeepAlive
   */
  protected $keepAlive;

  public function __construct(\Mqtt\Session\KeepAlive $keepAlive) {
    $this->keepAlive = $keepAlive;
  }

  public function start() : void {
    throw new \Exception('Already started');
  }

  public function stop() : void {
    $this->keepAlive->stop();

    $disconnectPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacket::DISCONNECT);
    $this->context->getProtocol()->writePacket($disconnectPacket);
    $this->context->getProtocol()->disconnect();
  }

  public function publish() : void {
  }

  public function subscribe() : void {
  }

  public function unsubscribe(): void {
  }

  public function onProtocolConnect(): void {
  }

  public function onProtocolDisconnect(): void {
    $this->keepAlive->stop();
    $this->stateChanger->setState(\Mqtt\Session\State\ISessionState::DISCONNECTED);
  }

  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {
    $this->keepAlive->onPacketReceived($packet);
  }

  public function onStateEnter(): void {
    $this->keepAlive->start();
  }

  public function onTick(): void {
    $this->keepAlive->onTick();
  }

}
