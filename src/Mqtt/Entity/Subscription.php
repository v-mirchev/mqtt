<?php

namespace Mqtt\Entity;

class Subscription implements IQoS {

  /**
   * @var \Mqtt\Entity\Topic
   */
  public $topic;

  /**
   * @var  \Mqtt\Client\Handler\ISubscription
   */
  public $handler;

  /**
   * @var bool
   */
  protected $isSubscribed;

  public function __construct(\Mqtt\Entity\Topic $topic) {
    $this->handler = new class implements \Mqtt\Client\Handler\ISubscription {
      public function onSubscribeAcknowledged(\Mqtt\Entity\Topic $topic): void {}
      public function onSubscribeFailed(\Mqtt\Entity\Topic $topic): void {}
      public function onSubscribed(\Mqtt\Entity\Topic $topic): void {}
      public function onSubscribeUnacknowledged(\Mqtt\Entity\Topic $topic): void {}
      public function onUnsubscribeUnacknowledged(Topic $topic): void {}
      public function onUnsubscribeAcknowledged(Topic $topic): void {}
    };
    $this->topic = clone $topic;
    $this->topic->qos->setRelated($this);
    $this->isSubscribed = false;
  }

  public function __clone() {
    $this->handler = new class implements \Mqtt\Client\Handler\ISubscription {
      public function onSubscribeAcknowledged(\Mqtt\Entity\Topic $topic): void {}
      public function onSubscribeFailed(\Mqtt\Entity\Topic $topic): void {}
      public function onSubscribed(\Mqtt\Entity\Topic $topic): void {}
      public function onSubscribeUnacknowledged(\Mqtt\Entity\Topic $topic): void {}
      public function onUnsubscribeUnacknowledged(Topic $topic): void {}
      public function onUnsubscribeAcknowledged(Topic $topic): void {}
    };
    $this->topic = clone $this->topic;
    $this->topic->qos->setRelated($this);
    $this->isSubscribed = false;
  }

  /**
   * @param string $name
   * @return \Mqtt\Entity\Subscription
   */
  public function topic(string $name) : \Mqtt\Entity\Subscription {
    $this->topic->name($name);
    return $this;
  }

  /**
   * @return \Mqtt\Entity\Subscription
   */
  public function atMostOnce() : \Mqtt\Entity\Subscription {
    return $this->topic->qos->atMostOnce();
  }

  /**
   * @return \Mqtt\Entity\Subscription
   */
  public function atLeastOnce() : \Mqtt\Entity\Subscription {
    return $this->topic->qos->atLeastOnce();
  }

  /**
   * @return \Mqtt\Entity\Subscription
   */
  public function exactlyOnce() : \Mqtt\Entity\Subscription {
    return $this->topic->qos->exactlyOnce();
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
   * @param type $subscribed
   * @return void
   */
  public function setAsSubscribed($subscribed = true) : void {
    $this->isSubscribed = $subscribed;
  }

  /**
   * @return bool
   */
  public function isSubscribed() : bool {
    return $this->isSubscribed;
  }

}
