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
   * @var \Mqtt\Protocol\Packet\Flow\Publishment\Publishments
   */
  protected $publishmentFlows;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow
   * @param \Mqtt\Protocol\Packet\Flow\KeepAlive\KeepAlive $keepAliveFlow
   * @param \Mqtt\Protocol\Packet\Flow\Subscription\Subscriptions $subscriptionFlows
   * @param \Mqtt\Protocol\Packet\Flow\Unsubscription\Unsubscriptions $unsubscriptionFlows
   * @param \Mqtt\Protocol\Packet\Flow\Publishment\Publishments $publishmentFlows
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow,
    \Mqtt\Protocol\Packet\Flow\KeepAlive\KeepAlive $keepAliveFlow,
    \Mqtt\Protocol\Packet\Flow\Subscription\Subscriptions $subscriptionFlows,
    \Mqtt\Protocol\Packet\Flow\Unsubscription\Unsubscriptions $unsubscriptionFlows,
    \Mqtt\Protocol\Packet\Flow\Publishment\Publishments $publishmentFlows
  ) {
    $this->connectionFlow = $connectionFlow;
    $this->keepAliveFlow = $keepAliveFlow;
    $this->subscriptionFlows = $subscriptionFlows;
    $this->unsubscriptionFlows = $unsubscriptionFlows;
    $this->publishmentFlows = $publishmentFlows;
  }

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    $this->keepAliveFlow->stop();
    $this->connectionFlow->stop();
    $this->subscriptionFlows->stop();
    $this->unsubscriptionFlows->stop();
    $this->publishmentFlows->stop();
  }

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function publish(\Mqtt\Entity\Message $message) : void {
    /* @var $publishPacket \Mqtt\Protocol\Packet\Type\Publish */
    $publishPacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacketType::PUBLISH);
    $publishPacket->topic = $message->topic;
    $publishPacket->content = $message->content;
    $publishPacket->dup = false;
    $publishPacket->retain = $message->isRetain;
    $publishPacket->qos = $message->qos->qos;
    $publishPacket->message = $message;
    $this->context->getProtocol()->writePacket($publishPacket);
  }

  public function subscribe(array $subscriptions) : void {
    /* @var $subscribePacket \Mqtt\Protocol\Packet\Type\Subscribe */
    $subscribePacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacketType::SUBSCRIBE);
    $subscribePacket->subscriptions = $subscriptions;
    $this->context->getProtocol()->writePacket($subscribePacket);
  }

  public function unsubscribe(array $subscriptions) : void {
    /* @var $unsubscribePacket \Mqtt\Protocol\Packet\Type\Unsubscribe */
    $unsubscribePacket = $this->context->getProtocol()->createPacket(\Mqtt\Protocol\IPacketType::UNSUBSCRIBE);
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

  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    $this->connectionFlow->onPacketReceived($packet);
    $this->keepAliveFlow->onPacketReceived($packet);
    $this->subscriptionFlows->onPacketReceived($packet);
    $this->unsubscriptionFlows->onPacketReceived($packet);
    $this->publishmentFlows->onPacketReceived($packet);
  }

  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    $this->connectionFlow->onPacketSent($packet);
    $this->keepAliveFlow->onPacketSent($packet);
    $this->subscriptionFlows->onPacketSent($packet);
    $this->unsubscriptionFlows->onPacketSent($packet);
    $this->publishmentFlows->onPacketSent($packet);
  }

  public function onStateEnter(): void {
    $this->keepAliveFlow->start();
    $this->subscriptionFlows->start();
    $this->unsubscriptionFlows->start();
    $this->publishmentFlows->start();
    $this->client->onConnect();
  }

  public function onTick(): void {
    $this->connectionFlow->onTick();
    $this->keepAliveFlow->onTick();
    $this->subscriptionFlows->onTick();
    $this->unsubscriptionFlows->onTick();
    $this->publishmentFlows->onTick();
  }

}
