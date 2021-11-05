<?php declare(strict_types = 1);

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

    $this->context->getIdProvider()->free($subscribePacket->id);
    $this->context->getSubscriptionsFlowQueue()->remove($subscribePacket->id);

    foreach ($subscribePacket->subscriptions as $subscriptionIndex => $subscription) {
      $subscription->handler->onSubscribeAcknowledged($subscription->topicFilter);
      /* @var $topic \Mqtt\Entity\Subscription */
      $topicReturnCodes = $subAckPacket->getReturnCodes();
      if ($topicReturnCodes[$subscriptionIndex] === static::ERROR) {
        $subscription->setAsSubscribed(false);
        $subscription->handler->onSubscribeFailed($subscription->topicFilter);
      } else {
        $subscription->setAsSubscribed();
        $subscription->handler->onSubscribed($subscription->topicFilter);
      }
    }

  }

}
