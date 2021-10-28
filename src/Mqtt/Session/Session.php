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
   * @var \Mqtt\IClient
   */
  protected $client;

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

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function publish(\Mqtt\Entity\Message $message) : void {
    $this->sessionState->publish($message);
  }

  /**
   * @param \Mqtt\Entity\Subscription[] $subscriptions
   * @return void
   */
  public function subscribe(array $subscriptions) : void {
    $this->sessionState->subscribe($subscriptions);
  }

  /**
   * @param \Mqtt\Entity\Subscription[] $subscriptions
   * @return void
   */
  public function unsubscribe(array $subscriptions) : void {
    $this->sessionState->unsubscribe($subscriptions);
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

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->sessionState->onPacketSent($packet);
  }

  public function onTick(): void {
    $this->sessionState->onTick();
    $this->client->onTick();
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
    $this->sessionState->setClient($this->client);
    $this->sessionState->onStateEnter();
    unset($previous);
  }

  /**
   * @param \Mqtt\Client\IClient $client
   * @return void
   */
  public function setClient(\Mqtt\Client\IClient $client): void {
    $this->client = $client;
  }

}
