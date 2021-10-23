<?php

namespace Mqtt\Session\State;

class Connected implements \Mqtt\Session\State\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Connection\Connection
   */
  protected $connectionFlow;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow
  ) {
    $this->connectionFlow = $connectionFlow;
  }

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    $this->protocol->disconnect();
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

  public function onProtocolDisconnect() : void {
    $this->stateChanger->setState(\Mqtt\Session\State\IState::DISCONNECTED);
  }

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->connectionFlow->onPacketReceived($packet);
  }

  public function onStateEnter(): void {
    $this->connectionFlow->start();
  }

  public function onTick(): void {
    $this->connectionFlow->onTick();
  }

  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {

  }

}
