<?php

namespace Mqtt\Session\State;

class Started implements \Mqtt\Session\State\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Connection\Connection
   */
  protected $connectionFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\KeepAlive\KeepAlive
   */
  protected $keepAliveFlow;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow
   * @param \Mqtt\Protocol\Packet\Flow\KeepAlive\KeepAlive $keepAliveFlow
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow,
    \Mqtt\Protocol\Packet\Flow\KeepAlive\KeepAlive $keepAliveFlow
  ) {
    $this->connectionFlow = $connectionFlow;
    $this->keepAliveFlow = $keepAliveFlow;
  }

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    $this->keepAliveFlow->stop();
    $this->connectionFlow->stop();
  }

  public function publish() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe() : void {
  }

  public function unsubscribe(): void {
  }

  public function onProtocolConnect(): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolDisconnect(): void {
    $this->connectionFlow->onProtocolDisconnect();
    $this->keepAliveFlow->onProtocolDisconnect();
    $this->stateChanger->setState(\Mqtt\Session\State\IState::DISCONNECTED);
  }

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->connectionFlow->onPacketReceived($packet);
    $this->keepAliveFlow->onPacketReceived($packet);
  }

  public function onStateEnter(): void {
    $this->keepAliveFlow->start();
  }

  public function onTick(): void {
    $this->connectionFlow->onTick();
    $this->keepAliveFlow->onTick();
  }

  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {

  }

}
