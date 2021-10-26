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

  const SUBSCRIBE_SUBSCRIBING = 'subscribe.subscribing';
  const SUBSCRIBE_ACK_WAITING = 'subscribe.ack.waiting';
  const SUBSCRIBE_ACKNOWLEDGED = 'subscribe.acknowledged';
  const SUBSCRIBE_UNACKNOWLEDGED = 'subscribe.unacknowledged';

  const UNSUBSCRIBE_UNSUBSCRIBING = 'unsubscribe.unsubscribing';
  const UNSUBSCRIBE_ACK_WAITING = 'unsubscribe.ack.waiting';
  const UNSUBSCRIBE_ACKNOWLEDGED = 'unsubscribe.acknowledged';
  const UNSUBSCRIBE_UNACKNOWLEDGED = 'unsubscribe.unacknowledged';

  public function start() : void;
  public function stop() : void;

  public function onStateEnter() : void;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\IStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Protocol\Packet\Flow\IStateChanger $stateChanger) : void;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\ISessionContext $context
   * @return void
   */
  public function setContext(\Mqtt\Protocol\Packet\Flow\ISessionContext $context) : void;


  /**
   * @param \Mqtt\Protocol\Packet\Flow\IFlowContext $context
   * @return void
   */
  public function setSubcontext(\Mqtt\Protocol\Packet\Flow\IFlowContext $context) : void;

}
