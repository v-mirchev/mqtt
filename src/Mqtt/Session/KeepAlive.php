<?php

namespace Mqtt\Session;

class KeepAlive implements \Mqtt\Session\ISession, \Mqtt\Session\ISessionStateChanger {

  use \Mqtt\Session\TSession;

  /**
   * @var \Mqtt\Entity\Configuration\Session
   */
  protected $configuration;

  /**
   * @var \Mqtt\Protocol\IProtocol
   */
  protected $protocol;

  /**
   * @var \Mqtt\State\ISession
   */
  protected $session;

  /**
   * @var \Mqtt\Session\State\Factory
   */
  protected $stateFactory;

  /**
   * @var \Mqtt\Session\ISessionContext
   */
  protected $context;

  /**
   * @var \Mqtt\Session\State\ISessionKeepAliveState
   */
  protected $sessionState;

  /**
   * @var string
   */
  protected $initialStateName;

  /**
   * @param \Mqtt\Entity\Configuration\Session $configuration
   * @param \Mqtt\Protocol\IProtocol $protocol
   * @param \Mqtt\State\ISession $session
   * @param \Mqtt\Session\State\Factory $stateFactory
   * @param \Mqtt\Session\ISessionContext $context
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Session $configuration,
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Session\ISession $session,
    \Mqtt\Session\State\Factory $stateFactory,
    \Mqtt\Session\ISessionContext $context
  ) {
    $this->configuration = $configuration;
    $this->protocol = $protocol;

    $this->session = $session;

    $this->stateFactory = $stateFactory;
    $this->context = $context;
    $this->context->setSession($this);
  }

  public function start() : void {
    $this->setState(
      $this->configuration->keepAliveInterval > 0 ?
        \Mqtt\Session\State\ISessionState::PING_WAIT:
        \Mqtt\Session\State\ISessionState::KEEP_ALIVE_DISABLED
    );
    $this->sessionState->start();
  }

  public function stop() : void {
    $this->sessionState->stop();
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

  public function onTick(): void {
    $this->sessionState->onTick();
  }

  /**
   * @param string $sessionStateName
   * @return void
   */
  public function setState(string $sessionStateName) : void {
    $previous = $this->sessionState;
    $this->sessionState = clone $this->stateFactory->create($sessionStateName);
    $this->sessionState->setStateChanger($this);
    $this->sessionState->setContext($this->context);
    unset($previous);

    $this->sessionState->onStateEnter();
  }

}
