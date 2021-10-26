<?php

namespace Mqtt\Protocol\Packet\Flow\Subscription\State;

class Acknowledged implements \Mqtt\Protocol\Packet\Flow\IState {

  const ERROR = 0x80;

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $subscribePacket \Mqtt\Protocol\Packet\Type\Subscribe */
    $subscribePacket = $this->flowContext->getOutgoingPacket();

    /* @var $subAckPacket \Mqtt\Protocol\Packet\Type\SubAck */
    $subAckPacket = $this->flowContext->getIncomingPacket();

    $this->context->getSubscriptionsFlowQueue()->remove($subscribePacket->id);

    foreach ($subscribePacket->subscriptions as $subscriptionIndex => $subscription) {
      $subscription->handler->onAcknowledged($subscription->topic);
      /* @var $topic \Mqtt\Entity\Subsription */
      $topicReturnCodes = $subAckPacket->getReturnCodes();
      if ($topicReturnCodes[$subscriptionIndex] === static::ERROR) {
        $subscription->handler->onFailed($subscription->topic);
      } else {
        $subscription->handler->onSubscribed($subscription->topic);
      }
    }

  }

}
