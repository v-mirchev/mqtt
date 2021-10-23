<?php

namespace Mqtt\Session;

class Session implements \Mqtt\Session\ISession, \Mqtt\Session\IStateChanger {

  /**
   * @var \Mqtt\Protocol\IProtocol
   */
  protected $protocol;

  /**
   * @var \Mqtt\Session\State\IState
   */
  protected $sessionState;

  /**
   * @var \Mqtt\Session\StateFactory
   */
  protected $stateFactory;

  /**
   * @param \Mqtt\Protocol\IProtocol $protocol
   * @param \Mqtt\Session\StateFactory $stateFactory
   */
  public function __construct(
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Session\StateFactory $stateFactory
  ) {
    $this->protocol = $protocol;
    $this->protocol->setSession($this);

    $this->stateFactory = $stateFactory;
  }

  public function start() : void {
    $this->setState(\Mqtt\Session\State\IState::NOT_CONNECTED);
    $this->sessionState->start();
  }

  public function stop() : void {
    $this->sessionState->stop();
  }

  public function publish() : void {
    $this->sessionState->publish();
  }

  public function subscribe() : void {
    $this->sessionState->subscribe();
  }

  public function unsubscribe(): void {
    $this->sessionState->unsubscribe();
  }

  public function onProtocolConnect(): void {
    $this->sessionState->onProtocolConnect();
  }

  public function onProtocolDisconnect(): void {
    $this->sessionState->onProtocolDisconnect();
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->sessionState->onPacketReceived($packet);
  }

  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->sessionState->onPacketSent($packet);
  }

  public function onTick(): void {
    $this->sessionState->onTick();
  }

  /**
   * @param string $sessionState
   * @return void
   */
  public function setState(string $sessionState) : void {
    error_log('SESSION::' . $sessionState);
    $previous = $this->sessionState;
    $this->sessionState = $this->stateFactory->create($sessionState);
    $this->sessionState->setStateChanger($this);
    $this->sessionState->onStateEnter();
    unset($previous);
  }

}
