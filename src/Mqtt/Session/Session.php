<?php declare(ticks = 1);

namespace Mqtt\Session;

class Session implements \Mqtt\Session\ISession, \Mqtt\Session\ISessionStateChanger {

  /**
   * @var \Mqtt\Protocol\IProtocol
   */
  protected $protocol;

  /**
   * @var \Mqtt\Session\State\ISessionState
   */
  protected $sessionState;

  /**
   * @var \Mqtt\Session\State\Factory
   */
  protected $stateFactory;

  /**
   * @var \Mqtt\Session\ISessionContext
   */
  protected $context;

  /**
   * @var \Mqtt\Protocol\Packet\Id\IProvider
   */
  protected $idProvider;

  /**
   * @param \Mqtt\Protocol\IProtocol $protocol
   * @param \Mqtt\Protocol\Packet\Id\IProvider $idProvider
   * @param \Mqtt\Session\State\Factory $stateFactory
   * @param \Mqtt\Session\ISessionContext $context
   */
  public function __construct(
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Protocol\Packet\Id\IProvider $idProvider,
    \Mqtt\Session\State\Factory $stateFactory,
    \Mqtt\Session\ISessionContext $context
  ) {
    $this->protocol = $protocol;
    $this->protocol->setSession($this);

    $this->idProvider = $idProvider;
    $this->stateFactory = $stateFactory;
    $this->context = $context;
    $this->context->setSession($this);
  }

  public function start() : void {
    $this->setState(\Mqtt\Session\State\ISessionState::DISCONNECTED);
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
    $previous = $this->sessionState;
    $this->sessionState = $this->stateFactory->create($sessionState);
    $this->sessionState->setStateChanger($this);
    $this->sessionState->setContext($this->context);
    $this->sessionState->onStateEnter();
    unset($previous);
  }

}
