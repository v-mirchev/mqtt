<?php declare(ticks = 1);

namespace Mqtt\Session;

class KeptAliveSession implements
  \Mqtt\Session\ISession,
  \Mqtt\Session\ISessionStateChanger,
  \Mqtt\Session\ISessionContext,
  \Mqtt\Session\ISessionKeepAliveStateChanger,
  \Mqtt\Session\ISessionKeepAliveContext,
  \Mqtt\ITimeoutHandler
{


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
   * @var \Mqtt\Timeout
   */
  protected $keppAliveTimeout;

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
   * @param str $inititalSessionState
   * @param \Mqtt\Session\State\Factory $stateFactory
   * @param \Mqtt\Timeout $keppAliveTimeout
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Session $configuration,
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Session\ISession $session,
    string $inititalSessionState,
    \Mqtt\Session\State\Factory $stateFactory,
    \Mqtt\Timeout $keppAliveTimeout
  ) {
    $this->configuration = $configuration;
    $this->protocol = $protocol;
    $this->protocol->setSession($this);

    $this->session = $session;
    $this->initialStateName = $inititalSessionState;

    $this->stateFactory = $stateFactory;

    $this->keppAliveTimeout = $keppAliveTimeout;
  }

  public function start() : void {
    $this->keppAliveTimeout->subscribe($this);
    $this->keppAliveTimeout->setInterval(ceil($this->configuration->keepAliveInterval / 2));

    $this->setKeepAliveState($this->initialStateName);
    $this->sessionState->start();

    $this->session->start();
  }

  public function stop() : void {
    $this->sessionState->stop();
    $this->session->stop();
  }

  public function publish() : void {
    $this->session->publish();
  }

  public function subscribe() : void {
    $this->session->subscribe();
  }

  public function unsubscribe(): void {
    $this->session->unsubscribe();
  }

  public function onProtocolConnect(): void {
    $this->sessionState->onProtocolConnect();
    $this->session->onProtocolConnect();
  }

  public function onProtocolDisconnect(): void {
    $this->sessionState->onProtocolDisconnect();
    $this->session->onProtocolDisconnect();
  }

  /**
   * @param \Mqtt\Protocol\IPacket $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {
    $this->sessionState->onPacketReceived($packet);
    $this->session->onPacketReceived($packet);
  }

  public function onTick(): void {
    $this->sessionState->onTick();
    $this->session->onTick();
  }

  /**
   * @param string $sessionState
   * @return void
   */
  public function setState(string $sessionState) : void {
    $this->session->setState($sessionState);
  }

  /**
   * @param string $sessionStateName
   * @return void
   */
  public function setKeepAliveState(string $sessionStateName) : void {
    $previous = $this->sessionState;
    $this->sessionState = clone $this->stateFactory->create($sessionStateName);
    $this->sessionState->setStateChanger($this);
    $this->sessionState->setContext($this);
    $this->sessionState->setKeepAliveContext($this);
    unset($previous);
  }

  public function onTimeout(): void {
    $this->sessionState->onTimeOut();
  }

  /**
   * @return \Mqtt\Timeout
   */
  public function getTimeoutWatcher(): \Mqtt\Timeout {
    return $this->keppAliveTimeout;
  }

  public function getIdProvider(): \Mqtt\IPacketIdProvider {
    return $this->session->getIdProvider();
  }

  public function getProtocol(): \Mqtt\Protocol\IProtocol {
    return $this->protocol;
  }

  public function getSession(): ISession {
    return $this->session;
  }

}
