<?php

namespace Mqtt\Protocol\Packet\Flow\KeepAlive;

class KeepAlive implements \Mqtt\Session\ISession, \Mqtt\Protocol\Packet\Flow\IStateChanger {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TStateChanger;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\IState
   */
  protected $flowState;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Factory
   */
  protected $stateFactory;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Factory $stateFactory
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Factory $stateFactory
  ) {
    $this->stateFactory = $stateFactory;
  }

  public function start() : void {
    $this->setState(\Mqtt\Protocol\Packet\Flow\IState::KEEP_ALIVE_CONFIGURE);
    $this->flowState->start();
  }

  public function stop() : void {
    $this->flowState->stop();
  }

  public function publish() : void {
    $this->flowState->publish();
  }

  public function subscribe() : void {
    $this->flowState->subscribe();
  }

  public function unsubscribe(): void {
    $this->flowState->unsubscribe();
  }

  public function onProtocolConnect(): void {
    $this->flowState->onProtocolConnect();
  }

  public function onProtocolDisconnect(): void {
    $this->flowState->onProtocolDisconnect();
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->flowState->onPacketReceived($packet);
  }

  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->flowState->onPacketSent($packet);
  }

  public function onTick(): void {
    $this->flowState->onTick();
  }

}
