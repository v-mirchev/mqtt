<?php

namespace Mqtt\Session\State;

interface ISessionKeepAliveState extends \Mqtt\Session\State\ISessionState {

  public function setKeepAliveContext(\Mqtt\Session\ISessionKeepAliveContext $context) : void;
  public function onTimeout() : void;

}
