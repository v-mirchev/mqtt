<?php

namespace Mqtt\Protocol\Packet\Flow;

interface IState {

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

  const PUBLISH_PUBLISHING = 'publish.publishing';

  const PUBLISH_INCOMING_PUBLISHING = 'publish.incoming.publishing';
  const PUBLISH_INCOMING_PUBLISHING_AT_MOST_ONCE = 'publish.incoming.publishing.at.most.once';
  const PUBLISH_INCOMING_PUBLISHING_AT_LEAST_ONCE = 'publish.incoming.publishing.at.least.once';
  const PUBLISH_INCOMING_PUBLISHING_EXACTLY_ONCE = 'publish.incoming.publishing.exactly.once';

  const PUBLISH_INCOMING_ACKNOWLEDGED = 'publish.incoming.acknowldged';

  const PUBLISH_INCOMING_RECEIVED = 'publish.incoming.received';
  const PUBLISH_INCOMING_RELEASE_WAITING = 'publish.incoming.release.waiting';
  const PUBLISH_INCOMING_COMPLETED = 'publish.incoming.completed';

  const PUBLISH_INCOMING_NOTIFY = 'publish.notify';

  const PUBLISH_OUTGOING_PUBLISHING = 'publish.outgoing.publishing';
  const PUBLISH_OUTGOING_PUBLISHING_AT_MOST_ONCE = 'publish.outgoing.publishing.at.most.once';
  const PUBLISH_OUTGOING_PUBLISHING_AT_LEAST_ONCE = 'publish.outgoing.publishing.at.least.once';
  const PUBLISH_OUTGOING_PUBLISHING_EXACTLY_ONCE = 'publish.outgoing.publishing.exactly.once';

  const PUBLISH_OUTGOING_ACK_WAITING = 'publish.outgoing.ack.waiting';
  const PUBLISH_OUTGOING_ACK_REPUBLISH = 'publish.outgoing.ack.republish';
  const PUBLISH_OUTGOING_ACKNOWLEDGED = 'publish.outgoing.acknowledged';

  const PUBLISH_OUTGOING_RECEIVED_WAITING = 'publish.outgoing.received.waiting';
  const PUBLISH_OUTGOING_RECEIVED = 'publish.outgoing.received';
  const PUBLISH_OUTGOING_RECEIVED_REPUBLISH = 'publish.outgoing.republish';
  const PUBLISH_OUTGOING_COMPLETED_WAITING = 'publish.outgoing.completed.waiting';
  const PUBLISH_OUTGOING_COMPLETED = 'publish.outgoing.completed';

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
