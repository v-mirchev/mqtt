<?php

namespace Mqtt\Protocol\Packet\Flow\Unsubscription\State;

class Unacknowledged implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $subscribePacket \Mqtt\Protocol\Packet\Type\Subscribe */
    $subscribePacket = $this->flowContext->getOutgoingPacket();

    $this->context->getUnsubscriptionsFlowQueue()->remove($subscribePacket->id);

    foreach ($subscribePacket->subscriptions as $subscription) {
      /* @var $subscription \Mqtt\Entity\Subscription */
      $subscription->handler->onUnsubscribeUnacknowledged($subscription->topic);
    }

  }

}
