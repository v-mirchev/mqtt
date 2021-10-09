<?php

namespace Mqtt\Session\State;

interface ISessionState extends \Mqtt\Session\ISession {

  const CONNECTED = 'connected';
  const DISCONNECTED = 'disconnected';

  public function setStateChanger(\Mqtt\Session\ISessionStateChanger $stateChanger) : void;
  public function setContext(\Mqtt\Session\ISessionContext $context) : void;

}
