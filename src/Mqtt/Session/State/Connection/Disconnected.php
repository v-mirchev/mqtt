<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class Disconnected implements \Mqtt\Session\State\ISessionState {

  use \Mqtt\Session\State\Connection\TSession;

  /**
   * @var \Mqtt\Entity\Configuration\Session
   */
  protected $sessionConfiguration;

  /**
   * @var \Mqtt\Entity\Configuration\Authentication
   */
  protected $authentication;

  /**
   * @var \Mqtt\Entity\Configuration\Will
   */
  protected $will;

  /**
   * @var \Mqtt\Session\ISessionStateChanger
   */
  protected $stateChanger;

  /**
   * @var \Mqtt\Session\ISessionContext
   */
  protected $context;

  /**
   * @param \Mqtt\Entity\Configuration\Session $sessionConfiguration
   * @param \Mqtt\Entity\Configuration\Authentication $authentication
   * @param \Mqtt\Entity\Configuration\Will $will
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Session $sessionConfiguration,
    \Mqtt\Entity\Configuration\Authentication $authentication,
    \Mqtt\Entity\Configuration\Will $will = null
  ) {
    $this->sessionConfiguration = $sessionConfiguration;
    $this->will = $will;
    $this->authentication = $authentication;
  }

  public function start() : void {
    $this->context->getProtocol()->connect();
  }

  public function onProtocolConnect(): void {
    $connectPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacket::CONNECT);
    $connectPacket->cleanSession = !$this->sessionConfiguration->isPersistent;
    $connectPacket->keepAliveInterval = $this->sessionConfiguration->keepAliveInterval;
    $connectPacket->will = $this->will;
    $connectPacket->clientId = $this->sessionConfiguration->clientId;
    $connectPacket->username = $this->authentication->username;
    $connectPacket->password = $this->authentication->password;
    $this->context->getProtocol()->writePacket($connectPacket);
  }

  public function onProtocolDisconnect(): void {
  }

  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet): void {
    if (!$packet instanceof \Mqtt\Protocol\Packet\ConnAck) {
      throw new \Exception('CONNACK packet expected');
    }

    if ($packet->getReturnCode() !== 0) {
      throw new \Exception($packet->getReturnCodeMessage() .', error code ' . $packet->getReturnCode());
    }

    $this->stateChanger->setState(\Mqtt\Session\State\ISessionState::CONNECTED);
  }

  public function onTick(): void {

  }

}
