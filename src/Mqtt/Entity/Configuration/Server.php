<?php

namespace Mqtt\Entity\Configuration;

class Server {

  /**
   * @var string
   */
  public $host;

  /**
   * @var int
   */
  public $port = 1883;

  /**
   * @var int
   */
  public $connectTimeout = 60;

  /**
   * @var int
   */
  public $timeout = 5;

  /**
   * @var int
   */
  public $selectTimeout = 20000;

    /**
   * @var string
   */
  public $certificateFile = null;

  /**
   * @param string $certificateFile
   * @return $this
   */
  public function certificate(string $certificateFile) : \Mqtt\Entity\Configuration\Server {
    $this->certificateFile = $certificateFile;
    return $this;
  }

  /**
   * @param string $host
   * @return $this
   */
  public function host(string $host) : \Mqtt\Entity\Configuration\Server {
    $this->host = $host;
    return $this;
  }

  /**
   * @param int $port
   * @return $this
   */
  public function port(int $port) : \Mqtt\Entity\Configuration\Server {
    $this->port = $port;
    return $this;
  }

  /**
   * @param int $timeout
   * @return $this
   */
  public function timeout(int $timeout) : \Mqtt\Entity\Configuration\Server {
    $this->timeout = $timeout;
    return $this;
  }

  /**
   * @param int $timeout
   * @return $this
   */
  public function connectTimeout(int $timeout) : \Mqtt\Entity\Configuration\Server {
    $this->connectTimeout = $timeout;
    return $this;
  }

  /**
   * @param int $selectTimeout
   * @return $this
   */
  public function selectTimeout(int $selectTimeout) : \Mqtt\Entity\Configuration\Server {
    $this->selectTimeout = $selectTimeout;
    return $this;
  }

}
