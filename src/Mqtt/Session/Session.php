<?php declare(ticks = 1);

namespace Mqtt\Session;

class Session implements
  \Mqtt\Session\ISession,
  \Mqtt\Session\ISessionStateChanger,
  \Mqtt\Session\ISessionContext
{

  /**
   * @var \Mqtt\Protocol\IProtocol
   */
  protected $protocol;

  /**
   * @var \Mqtt\Session\State\ISessionState
   */
  protected $sessionState;

  /**
   * @var \Psr\Container\ContainerInterface
   */
  protected $dic;

  /**
   * @var \Mqtt\PacketIdProvider\IPacketIdProvider
   */
  protected $idProvider;

  /**
   * @param \Mqtt\Protocol\IProtocol $protocol
   * @param \Mqtt\Session\State\ISessionState $session
   * @param \Mqtt\PacketIdProvider\IPacketIdProvider $idProvider
   * @param \Psr\Container\ContainerInterface $dic
   */
  public function __construct(
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Session\State\ISessionState $session,
    \Mqtt\PacketIdProvider\IPacketIdProvider $idProvider,
    \Psr\Container\ContainerInterface $dic
  ) {
    $this->protocol = $protocol;
    $this->protocol->setSession($this);

    $this->sessionState = $session;
    $this->sessionState->setStateChanger($this);
    $this->sessionState->setContext($this);

    $this->idProvider = $idProvider;
    $this->dic = $dic;
  }

  public function start() : void {
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
   * @param \Mqtt\Protocol\IPacket $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {
    $this->sessionState->onPacketReceived($packet);
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
    $this->sessionState = clone $this->dic->get($sessionState);
    $this->sessionState->setStateChanger($this);
    $this->sessionState->setContext($this);
    unset($previous);
  }

  /**
   * @return \Mqtt\Protocol\IProtocol
   */
  public function getProtocol(): \Mqtt\Protocol\IProtocol {
    return $this->protocol;
  }

  /**
   * @return \Mqtt\IPacketIdProvider
   */
  public function getIdProvider(): \Mqtt\IPacketIdProvider {
    return $this->idProvider;
  }

}
