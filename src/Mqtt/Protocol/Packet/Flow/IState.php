<?php

namespace Mqtt\Protocol\Packet\Flow;

interface IState extends \Mqtt\Protocol\IHandler {

  const NOT_CONNECTED = 'session.not.connected';
  const CONNECTED = 'session.connected';
  const CONNECTING = 'session.connecting';
  const DISCONNECTED = 'session.disconnected';

  const KEEP_ALIVE_CONFIGURE = 'keepalive.configure';
  const KEEP_ALIVE_DISABLED = 'keepalive.disabled';
  const KEEP_ALIVE_ENABLED = 'keepalive.enabled';
  const KEEP_ALIVE_PING_WAIT = 'keepalive.ping.wait';
  const KEEP_ALIVE_PONG_WAIT = 'keepalive.pong.wait';

  public function onStateEnter() : void;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\IStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Protocol\Packet\Flow\IStateChanger $stateChanger) : void;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\IContext $context
   * @return void
   */
  public function setContext(\Mqtt\Protocol\Packet\Flow\IContext $context) : void;

}
