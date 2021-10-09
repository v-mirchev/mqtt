<?php

namespace Mqtt\Session;

interface ISessionContext {

  /**
   * @return \Mqtt\Session\ISession
   */
  public function getSession() : \Mqtt\Session\ISession;

  /**
   * @return \Mqtt\IPacketIdProvider
   */
  public function getIdProvider() : \Mqtt\IPacketIdProvider;

  /**
   * @return \Mqtt\Protocol\IProtocol
   */
  public function getProtocol() : \Mqtt\Protocol\IProtocol;

}
