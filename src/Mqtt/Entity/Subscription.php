<?php declare(strict_types = 1);

namespace Mqtt\Entity;

class Subscription implements IQoS {

  /**
   * @var \Mqtt\Entity\TopicFilter
   */
  public $topicFilter;

  /**
   * @var  \Mqtt\Client\Handler\ISubscription
   */
  public $handler;

  /**
   * @var bool
   */
  protected $isSubscribed;

  public function __construct(\Mqtt\Entity\TopicFilter $topicFilter) {
    $this->handler = new class implements \Mqtt\Client\Handler\ISubscription {
      public function onSubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribeFailed(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribed(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onUnsubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onUnsubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onMessage(\Mqtt\Entity\Message $message): void {}
    };
    $this->topicFilter = clone $topicFilter;
    $this->topicFilter->qos->setRelated($this);
    $this->isSubscribed = false;
  }

  public function __clone() {
    $this->handler = new class implements \Mqtt\Client\Handler\ISubscription {
      public function onSubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribeFailed(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribed(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onUnsubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onUnsubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onMessage(\Mqtt\Entity\Message $message): void {}
    };
    $this->topicFilter = clone $this->topicFilter;
    $this->topicFilter->qos->setRelated($this);
    $this->isSubscribed = false;
  }

  /**
   * @param string $filter
   * @return \Mqtt\Entity\Subscription
   */
  public function topicFilter(string $filter) : \Mqtt\Entity\Subscription {
    $this->topicFilter->filter($filter);
    return $this;
  }

  /**
   * @return \Mqtt\Entity\Subscription
   */
  public function atMostOnce() : \Mqtt\Entity\Subscription {
    return $this->topicFilter->qos->atMostOnce();
  }

  /**
   * @return \Mqtt\Entity\Subscription
   */
  public function atLeastOnce() : \Mqtt\Entity\Subscription {
    return $this->topicFilter->qos->atLeastOnce();
  }

  /**
   * @return \Mqtt\Entity\Subscription
   */
  public function exactlyOnce() : \Mqtt\Entity\Subscription {
    return $this->topicFilter->qos->exactlyOnce();
  }

  /**
   * @param \Mqtt\Client\Handler\ISubscription $handler
   * @return \Mqtt\Entity\Subscription
   */
  public function handler(\Mqtt\Client\Handler\ISubscription $handler) : \Mqtt\Entity\Subscription {
    $this->handler = $handler;
    return $this;
  }

  /**
   * @param bool $subscribed
   * @return \Mqtt\Entity\Subscription
   */
  public function setAsSubscribed($subscribed = true) : \Mqtt\Entity\Subscription {
    $this->isSubscribed = $subscribed;
    return $this;
  }

  /**
   * @return bool
   */
  public function isSubscribed() : bool {
    return $this->isSubscribed;
  }

}
