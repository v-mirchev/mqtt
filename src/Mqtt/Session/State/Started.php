<?php

namespace Mqtt\Session\State;

class Started implements \Mqtt\Session\State\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Connection\Connection
   */
  protected $connectionFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\KeepAlive\KeepAlive
   */
  protected $keepAliveFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Subscription\Subscriptions
   */
  protected $subscriptionFlows;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Unsubscription\Unsubscriptions
   */
  protected $unsubscriptionFlows;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow
   * @param \Mqtt\Protocol\Packet\Flow\KeepAlive\KeepAlive $keepAliveFlow
   * @param \Mqtt\Protocol\Packet\Flow\Subscription\Subscriptions $subscriptionFlows
   * @param \Mqtt\Protocol\Packet\Flow\Unsubscription\Unsubscriptions $unsubscriptionFlows
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow,
    \Mqtt\Protocol\Packet\Flow\KeepAlive\KeepAlive $keepAliveFlow,
    \Mqtt\Protocol\Packet\Flow\Subscription\Subscriptions $subscriptionFlows,
    \Mqtt\Protocol\Packet\Flow\Unsubscription\Unsubscriptions $unsubscriptionFlows
  ) {
    $this->connectionFlow = $connectionFlow;
    $this->keepAliveFlow = $keepAliveFlow;
    $this->subscriptionFlows = $subscriptionFlows;
    $this->unsubscriptionFlows = $unsubscriptionFlows;
  }

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    $this->keepAliveFlow->stop();
    $this->connectionFlow->stop();
    $this->subscriptionFlows->stop();
    $this->unsubscriptionFlows->stop();
  }

  public function publish() : void {
  }

  public function subscribe(array $subscriptions) : void {
    $subscribePacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::SUBSCRIBE);
    $subscribePacket->subscriptions = $subscriptions;
    $this->context->getProtocol()->writePacket($subscribePacket);
  }

  public function unsubscribe(array $subscriptions) : void {
    $unsubscribePacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\Packet\IType::UNSUBSCRIBE);
    $unsubscribePacket->subscriptions = $subscriptions;
    $this->context->getProtocol()->writePacket($unsubscribePacket);
  }

  public function onProtocolConnect(): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolDisconnect(): void {
    $this->connectionFlow->onProtocolDisconnect();
    $this->keepAliveFlow->onProtocolDisconnect();
    $this->stateChanger->setState(\Mqtt\Session\State\IState::DISCONNECTED);
  }

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->connectionFlow->onPacketReceived($packet);
    $this->keepAliveFlow->onPacketReceived($packet);
    $this->subscriptionFlows->onPacketReceived($packet);
    $this->unsubscriptionFlows->onPacketReceived($packet);
    print_r($packet);
  }

  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->connectionFlow->onPacketSent($packet);
    $this->keepAliveFlow->onPacketSent($packet);
    $this->subscriptionFlows->onPacketSent($packet);
    $this->unsubscriptionFlows->onPacketSent($packet);
    print_r($packet);
  }

  public function onStateEnter(): void {
    $this->keepAliveFlow->start();
    $this->subscriptionFlows->start();
    $this->unsubscriptionFlows->start();
    $this->client->onConnect();
  }

  public function onTick(): void {
    $this->connectionFlow->onTick();
    $this->keepAliveFlow->onTick();
    $this->subscriptionFlows->onTick();
    $this->unsubscriptionFlows->onTick();
  }

}
