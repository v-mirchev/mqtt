<?php

namespace Mqtt\Session\State;

interface ISessionState extends \Mqtt\Session\ISession {

  const CONNECTED = 'connected';
  const DISCONNECTED = 'disconnected';
  const PING_WAIT = 'ping.wait';
  const PONG_WAIT = 'pong.wait';

  public function onStateEnter() : void;

  /**
   * @param \Mqtt\Session\ISessionStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Session\ISessionStateChanger $stateChanger) : void;

  /**
   * @param \Mqtt\Session\ISessionContext $context
   * @return void
   */
  public function setContext(\Mqtt\Session\ISessionContext $context) : void;

}
