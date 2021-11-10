<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming;

class Notify implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @var \Mqtt\Entity\Message
   */
  protected $messagePrototype;

  /**
   * @param \Mqtt\Entity\Message $messagePrototype
   */
  public function __construct(\Mqtt\Entity\Message $messagePrototype) {
    $this->messagePrototype = $messagePrototype;
  }

  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getIncomingPacket();

    if ($packet->id) {
      $this->context->getPublishmentIncomingFlowQueue()->remove($packet->id);
    }

    foreach ($this->context->getSubscriptions()->getAllByTopicFilter($packet->topic) as $subscription) {
      /* @var $subscription \Mqtt\Entity\Subscription */
      $message = clone $this->messagePrototype;
      $message->content = $packet->content;
      $message->topic = $packet->topic;
      $subscription->handler->onMessage($message);
    }

  }

}
