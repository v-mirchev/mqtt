<?php declare(ticks = 1);

namespace Mqtt\Session\State\Session;

class Connected implements \Mqtt\Session\State\ISessionState {

  use \Mqtt\Session\State\Session\TSession;

  /**
   * @var \Mqtt\Session\ISessionStateChanger
   */
  protected $stateChanger;

  /**
   * @var \Mqtt\Session\ISessionContext
   */
  protected $context;

  public function start() : void {
    throw new \Exception('Already started');
  }

  public function stop() : void {
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

  public function onConnect(): void {
    throw new \Exception('Already connected');
  }

  public function onDisconnect(): void {
    $this->stateChanger->setState(\Mqtt\Session\State\Session\Disconnected::class);
  }

  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {
  }

  public function onTick(): void {

  }

}