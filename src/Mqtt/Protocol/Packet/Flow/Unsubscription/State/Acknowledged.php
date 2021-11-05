<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Unsubscription\State;

class Acknowledged implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function onStateEnter(): void {
    /* @var $unsubscribePacket \Mqtt\Protocol\Packet\Type\Subscribe */
    $unsubscribePacket = $this->flowContext->getOutgoingPacket();

    $this->context->getIdProvider()->free($unsubscribePacket->id);
    $this->context->getUnsubscriptionsFlowQueue()->remove($unsubscribePacket->id);

    foreach ($unsubscribePacket->subscriptions as $subscription) {
      /* @var $subscription \Mqtt\Entity\Subscription */
      $subscription->setAsSubscribed(false);
      $this->context->getSubscriptions()->remove($subscription);
      $subscription->handler->onUnsubscribeAcknowledged($subscription->topicFilter);
    }

  }

}
