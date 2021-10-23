<?php

namespace Mqtt\Session\State;

interface IState extends \Mqtt\Session\ISession {

  const NOT_CONNECTED = 'not.connected';
  const CONNECTED = 'connected';
  const CONNECTING = 'connecting';
  const DISCONNECTED = 'disconnected';
  const DISCONNECTING = 'disconnecting';
  const STARTED = 'started';

  const KEEP_ALIVE_DISABLED = 'keepalive.disabled';

  public function onStateEnter() : void;

  /**
   * @param \Mqtt\Session\IStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Session\IStateChanger $stateChanger) : void;

  /**
   * @param \Mqtt\Session\IContext $context
   * @return $this
   */
  public function setContext(\Mqtt\Session\IContext $context);
}
