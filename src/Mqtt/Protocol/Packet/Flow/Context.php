<?php

namespace Mqtt\Protocol\Packet\Flow;

class Context implements \Mqtt\Protocol\Packet\Flow\IContext {

  /**
   * @var \Mqtt\Protocol\IProtocol
   */
  protected $protocol;

  /**
   * @var \Mqtt\Session\IStateChanger
   */
  protected $sessionStateChanger;

  /**
   * @var \Mqtt\Protocol\Packet\Id\IProvider
   */
  protected $idProvider;

  /**
   * @var \Mqtt\Entity\Configuration\Session
   */
  protected $sessionConfiguration;

  /**
   * @param \Mqtt\Protocol\IProtocol $protocol
   * @param \Mqtt\Session\IStateChanger $sessionStateChanger
   * @param \Mqtt\Protocol\Packet\Id\IProvider $idProvider
   * @param \Mqtt\Entity\Configuration\Session $sessionConfiguration
   */
  public function __construct(
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Session\IStateChanger $sessionStateChanger,
    \Mqtt\Protocol\Packet\Id\IProvider $idProvider,
    \Mqtt\Entity\Configuration\Session $sessionConfiguration
  ) {
    $this->protocol = $protocol;
    $this->sessionStateChanger = $sessionStateChanger;
    $this->idProvider = $idProvider;
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
   * @return \Mqtt\Session\IStateChanger
   */
  public function getSessionStateChanger(): \Mqtt\Session\IStateChanger {
    return $this->sessionStateChanger;
  }

  /**
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function getSessionConfiguration(): \Mqtt\Entity\Configuration\Session {
    return $this->sessionConfiguration;
  }

}