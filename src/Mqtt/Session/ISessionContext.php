<?php

namespace Mqtt\Session;

interface ISessionContext {

  /**
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function getSessionConfiguration() : \Mqtt\Entity\Configuration\Session;

  /**
   * @return \Mqtt\Session\ISession
   */
  public function getSession() : \Mqtt\Session\ISession;

  /**
   * @param \Mqtt\Session\ISession $session
   */
  public function setSession(\Mqtt\Session\ISession $session);

  /**
   * @return \Mqtt\Protocol\Packet\Id\IProvider
   */
  public function getIdProvider() : \Mqtt\Protocol\Packet\Id\IProvider;

  /**
   * @return \Mqtt\Protocol\IProtocol
   */
  public function getProtocol() : \Mqtt\Protocol\IProtocol;

}
