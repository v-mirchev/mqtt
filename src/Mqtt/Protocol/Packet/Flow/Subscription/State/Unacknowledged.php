<?php

namespace Mqtt\Protocol\Packet\Flow\Subscription\State;

class Unacknowledged implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $subscribePacket \Mqtt\Protocol\Packet\Type\Subscribe */
    $subscribePacket = $this->flowContext->getOutgoingPacket();

    $this->context->getSubscriptionsFlowQueue()->remove($subscribePacket->id);

    foreach ($subscribePacket->subscriptions as $subscriptionIndex => $subscription) {
      $subscription->handler->onUnacknowledged($subscription->topic);
    }

  }

}
