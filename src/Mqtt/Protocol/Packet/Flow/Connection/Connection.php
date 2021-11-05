<?php

namespace Mqtt\Protocol\Packet\Flow\Connection;

class Connection implements \Mqtt\Session\ISession, \Mqtt\Protocol\Packet\Flow\IStateChanger {

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
    $this->setState(\Mqtt\Protocol\Packet\Flow\IState::NOT_CONNECTED);
    $this->flowState->start();
  }

  public function stop() : void {
    $this->flowState->stop();
  }

  public function onProtocolConnect(): void {
    $this->flowState->onProtocolConnect();
  }

  public function onProtocolDisconnect(): void {
    $this->flowState->onProtocolDisconnect();
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    $this->flowState->onPacketReceived($packet);
  }

  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    $this->flowState->onPacketSent($packet);
  }

  public function onTick(): void {
    $this->flowState->onTick();
  }

}
