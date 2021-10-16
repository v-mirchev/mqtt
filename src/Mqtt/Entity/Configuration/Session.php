<?php

namespace Mqtt\Entity\Configuration;

class Session {

  /**
   * @var string
   */
  public $clientId;

  /**
   * @var int
   */
  public $keepAliveInterval = 60;

  /**
   * @var int
   */
  public $connectAcknowledgeTimeout = 10;

  /**
   * @var bool
   */
  public $isPersistent;

  /**
   * @var \Mqtt\Entity\Configuration\Authentication
   */
  public $authentication;

  /**
   * @var \Mqtt\Entity\Configuration\Will
   */
  public $will;

  /**
   * @var \Mqtt\Entity\Configuration\Protocol
   */
  public $protocol;

  /**
   * @var bool
   */
  public $useWill;

  /**
   * @var bool
   */
  public $useAuthentication;

  /**
   * @param \Mqtt\Entity\Configuration\Authentication $authentication
   * @param \Mqtt\Entity\Configuration\Will $will
   * @param \Mqtt\Entity\Configuration\Protocol $protocol
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Authentication $authentication,
    \Mqtt\Entity\Configuration\Will $will,
    \Mqtt\Entity\Configuration\Protocol $protocol
  ) {
    $this->authentication = $authentication;
    $this->will = $will;
    $this->protocol = $protocol;

    $this->clientId = '';
    $this->keepAliveInterval = 0;
    $this->isPersistent = false;
    $this->useAuthentication = false;
    $this->useWill = false;
  }

  /**
   * @param bool $useAuthentication
   * @return \Mqtt\Entity\Configuration\Authentication
   */
  public function authentication($useAuthentication = true) : \Mqtt\Entity\Configuration\Authentication {
    $this->useAuthentication = $useAuthentication;
    return $this->authentication;
  }

  /**
   * @param bool $useWill
   * @return \Mqtt\Entity\Configuration\Will
   */
  public function will($useWill = true) : \Mqtt\Entity\Configuration\Will {
    $this->useWill = $useWill;
    return $this->will;
  }

  /**
   * @return \Mqtt\Entity\Configuration\Protocol
   */
  public function protocol() : \Mqtt\Entity\Configuration\Protocol {
    return $this->protocol;
  }

  /**
   * @param string $clientId
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function clientId(string $clientId) : \Mqtt\Entity\Configuration\Session {
    $this->clientId = $clientId;
    return $this;
  }

  /**
   * @param int $keepAliveInterval
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function keepAliveInterval(int $keepAliveInterval) : \Mqtt\Entity\Configuration\Session {
    $this->keepAliveInterval = $keepAliveInterval;
    return $this;
  }

  /**
   * @param int $connectAcknowledgeTimeout
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function connectAcknowledgeTimeout(int $connectAcknowledgeTimeout) : \Mqtt\Entity\Configuration\Session {
    $this->connectAcknowledgeTimeout = $connectAcknowledgeTimeout;
    return $this;
  }

  /**
   * @param bool $isPersistent
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function usePersistent(bool $isPersistent = true) : \Mqtt\Entity\Configuration\Session {
    $this->isPersistent = $isPersistent;
    return $this;
  }

}
