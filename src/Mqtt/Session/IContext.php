<?php declare(strict_types = 1);

namespace Mqtt\Session;

interface IContext {

  /**
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function getSessionConfiguration() : \Mqtt\Entity\Configuration\Session;

  /**
   * @return \Mqtt\Protocol\IProtocol
   */
  public function getProtocol() : \Mqtt\Protocol\IProtocol;

}
