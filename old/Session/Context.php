<?php declare(strict_types = 1);

namespace Mqtt\Session;

class Context implements \Mqtt\Session\IContext {

  /**
   * @var \Mqtt\Protocol\IProtocol
   */
  protected $protocol;

  /**
   * @var \Mqtt\Entity\Configuration\Session
   */
  protected $sessionConfiguration;

  /**
   * @param \Mqtt\Protocol\IProtocol $protocol
   * @param \Mqtt\Entity\Configuration\Session $sessionConfiguration
   */
  public function __construct(
    \Mqtt\Protocol\IProtocol $protocol,
    \Mqtt\Entity\Configuration\Session $sessionConfiguration
  ) {
    $this->protocol = $protocol;
    $this->sessionConfiguration = $sessionConfiguration;
  }

  /**
   * @return \Mqtt\Protocol\IProtocol
   */
  public function getProtocol(): \Mqtt\Protocol\IProtocol {
    return $this->protocol;
  }

  /**
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function getSessionConfiguration(): \Mqtt\Entity\Configuration\Session {
    return $this->sessionConfiguration;
  }

}
