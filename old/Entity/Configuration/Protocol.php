<?php declare(strict_types = 1);

namespace Mqtt\Entity\Configuration;

class Protocol {

  const MQTT = 'MQTT';
  const VERSION_3_1_1 = 4;

  /**
   * @var string
   */
  public $protocol;

  /**
   * @var string
   */
  public $version;

  public function __construct() {
    $this->protocol = static::MQTT;
    $this->version = static::VERSION_3_1_1;
  }

  /**
   * @param string $version
   * @return $this
   */
  public function version(string $version) : \Mqtt\Entity\Configuration\Protocol {
    $this->version = $version;
    return $this;
  }

  /**
   * @param string $protocol
   * @return $this
   */
  public function protocol(string $protocol) : \Mqtt\Entity\Configuration\Protocol {
    $this->protocol = $protocol;
    return $this;
  }

}
