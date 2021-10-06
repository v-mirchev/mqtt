<?php

namespace Mqtt\Session\State;

interface ISessionState extends \Mqtt\Session\ISession {

  public function setStateChanger(\Mqtt\Session\ISessionStateChanger $stateChanger) : void;
  public function setContext(\Mqtt\Session\ISessionContext $context) : void;

}
