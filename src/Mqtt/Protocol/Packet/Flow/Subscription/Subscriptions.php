<?php

namespace Mqtt\Protocol\Packet\Flow\Subscription;

class Subscriptions implements \Mqtt\Session\ISession {

  use \Mqtt\Session\TSession;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Subscription\Subscription
   */
  protected $subscriptionFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\ISessionContext
   */
  protected $context;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Subscription\Subscription $subscriptionFlow
   * @param \Mqtt\Protocol\Packet\Flow\ISessionContext $context
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Subscription\Subscription $subscriptionFlow,
    \Mqtt\Protocol\Packet\Flow\ISessionContext $context
  ) {
    $this->subscriptionFlow = $subscriptionFlow;
    $this->context = $context;
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::SUBACK)) {
      $this->context->getSubscriptionsFlowQueue()->get($packet->id)->onPacketReceived($packet);
    }
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::SUBSCRIBE)) {
      $subscriptionFlow = clone $this->subscriptionFlow;
      $subscriptionFlow->start();
      $subscriptionFlow->onPacketSent($packet);
      $this->context->getSubscriptionsFlowQueue()->add($packet->id, $subscriptionFlow);
    }
  }

  public function onTick(): void {
    foreach ($this->context->getSubscriptionsFlowQueue() as $subscription) {
      $subscription->onTick();
    }
  }

}
