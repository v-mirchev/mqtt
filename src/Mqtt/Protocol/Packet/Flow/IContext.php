<?php

namespace Mqtt\Protocol\Packet\Flow;

interface IContext {

  /**
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function getSessionConfiguration() : \Mqtt\Entity\Configuration\Session;

  /**
   * @return \Mqtt\Session\IStateChanger
   */
  public function getSessionStateChanger() : \Mqtt\Session\IStateChanger;

  /**
   * @return \Mqtt\Protocol\Packet\Id\IProvider
   */
  public function getIdProvider() : \Mqtt\Protocol\Packet\Id\IProvider;

  /**
   * @return \Mqtt\Protocol\IProtocol
   */
  public function getProtocol() : \Mqtt\Protocol\IProtocol;

}
