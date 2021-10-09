<?php

namespace Mqtt\Session\State;

interface ISessionState extends \Mqtt\Session\ISession {

  const CONNECTED = 'connected';
  const DISCONNECTED = 'disconnected';
  const PING_WAIT = 'ping.wait';
  const PONG_WAIT = 'pong.wait';

  public function setStateChanger(\Mqtt\Session\ISessionStateChanger $stateChanger) : void;
  public function setContext(\Mqtt\Session\ISessionContext $context) : void;

}
