<?php

namespace Mqtt\Session\State;

class Context implements \Mqtt\Session\ISessionContext {

  /**
   * @var \Mqtt\Session\ISession
   */
  protected $session;

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
   * @var \Mqtt\Protocol\IProtocol
   */
  protected $protocol;

  /**
   * @var \Mqtt\Session\State\Factory
   */
  protected $stateFactory;

  /**
   * @var \Mqtt\PacketIdProvider\IPacketIdProvider
   */
  protected $idProvider;

  /**
   * @param \Mqtt\Protocol\IProtocol $protocol
   * @param \Mqtt\PacketIdProvider\IPacketIdProvider $idProvider
   * @param \Mqtt\Session\State\Factory $stateFactory
   * @param \Mqtt\Entity\Configuration\Session $sessionConfiguration
   * @param \Mqtt\Entity\Configuration\Authentication $authentication
   * @param \Mqtt\Entity\Configuration\Will $will
   */
  public function __construct(
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\PacketIdProvider\IPacketIdProvider $idProvider,
    \Mqtt\Session\State\Factory $stateFactory,
    \Mqtt\Entity\Configuration\Session $sessionConfiguration
  ) {
    $this->protocol = $protocol;
    $this->idProvider = $idProvider;
    $this->stateFactory = $stateFactory;

    $this->sessionConfiguration = $sessionConfiguration;
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

  /**
   * @param \Mqtt\Session\ISession $session
   */
  public function setSession(\Mqtt\Session\ISession $session) {
    $this->session = $session;
  }

  /**
   * @return \Mqtt\Session\ISession
   */
  public function getSession(): \Mqtt\Session\ISession {
    return $this->session;
  }

  /**
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function getSessionConfiguration(): \Mqtt\Entity\Configuration\Session {
    return $this->sessionConfiguration;
  }

}
