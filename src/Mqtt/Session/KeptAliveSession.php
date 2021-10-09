<?php declare(ticks = 1);

namespace Mqtt\Session;

class KeptAliveSession implements
  \Mqtt\Session\ISession,
  \Mqtt\Session\ISessionStateChanger
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
   * @var \Mqtt\Session\ISessionContext
   */
  protected $context;

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
   * @param \Mqtt\Session\ISessionContext $context
   * @param \Mqtt\Timeout $keppAliveTimeout
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Session $configuration,
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Session\ISession $session,
    string $inititalSessionState,
    \Mqtt\Session\State\Factory $stateFactory,
    \Mqtt\Session\ISessionContext $context,
    \Mqtt\Timeout $keppAliveTimeout
  ) {
    $this->configuration = $configuration;
    $this->protocol = $protocol;
    $this->protocol->setSession($this);

    $this->session = $session;
    $this->initialStateName = $inititalSessionState;

    $this->stateFactory = $stateFactory;
    $this->context = $context;
    $this->context->setSession($this);

    $this->keppAliveTimeout = $keppAliveTimeout;
    $this->keppAliveTimeout->setInterval(ceil($this->configuration->keepAliveInterval / 3));
  }

  public function start() : void {
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
    $this->setDelegatedStateChanger();
    $this->sessionState->setContext($this->context);
    unset($previous);

    $this->sessionState->onStateEnter();
  }

  public function setDelegatedStateChanger() {
    $this->sessionState->setStateChanger(new class($this) implements ISessionStateChanger {
      /**
       * @var KeptAliveSession
       */
      protected $self;

      public function __construct(KeptAliveSession $self) {
        $this->self = $self;
      }

      public function setState(string $sessionState): void {
        $this->self->setKeepAliveState($sessionState);
      }
    });
  }

}
