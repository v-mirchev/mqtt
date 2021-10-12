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
   * @var \Mqtt\Session\State\Factory
   */
  protected $stateFactory;

  /**
   * @var \Mqtt\Protocol\Packet\Id\IProvider
   */
  protected $idProvider;

  /**
   * @param \Mqtt\Protocol\IProtocol $protocol
   * @param \Mqtt\Protocol\Packet\Id\IProvider $idProvider
   * @param \Mqtt\Session\State\Factory $stateFactory
   * @param \Mqtt\Entity\Configuration\Session $sessionConfiguration
   * @param \Mqtt\Entity\Configuration\Authentication $authentication
   * @param \Mqtt\Entity\Configuration\Will $will
   */
  public function __construct(
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Protocol\Packet\Id\IProvider $idProvider,
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
   * @return \Mqtt\Protocol\Packet\Id\IProvider
   */
  public function getIdProvider(): \Mqtt\Protocol\Packet\Id\IProvider {
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
