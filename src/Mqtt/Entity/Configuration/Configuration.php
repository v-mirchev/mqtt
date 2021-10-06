<?php

namespace Mqtt\Entity\Configuration;

class Configuration {

  /**
   * @var \Mqtt\Entity\Configuration\Server
   */
  protected $server;

  /**
   * @var \Mqtt\Entity\Configuration\Session
   */
  protected $session;

  /**
   * @param \Mqtt\Entity\Configuration\Server $server
   * @param \Mqtt\Entity\Configuration\Session $session
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Server $server,
    \Mqtt\Entity\Configuration\Session $session
  ) {
    $this->server = $server;
    $this->session = $session;
  }

  public function server(): \Mqtt\Entity\Configuration\Server {
    return $this->server;
  }

  public function session(): \Mqtt\Entity\Configuration\Session {
    return $this->session;
  }

}
