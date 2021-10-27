<?php

namespace Mqtt\Client;

class Subscriptions {

  /**
   * @var \Mqtt\Entity\Subscription[]
   */
  protected $subscriptions;

  /**
   * @var \Mqtt\Entity\Subscription
   */
  protected $subscriptionPrototype;

  /**
   * @param \Mqtt\Entity\Subscription $subscriptionPrototype
   */
  public function __construct(\Mqtt\Entity\Subscription $subscriptionPrototype) {
    $this->subscriptionPrototype = clone $subscriptionPrototype;
  }

  /**
   * @return \Mqtt\Entity\Subscription $subscription
   */
  public function create() : \Mqtt\Entity\Subscription {
    return clone $this->subscriptionPrototype;
  }

  /**
   * @param \Mqtt\Entity\Subscription $subscription
   */
  public function add(\Mqtt\Entity\Subscription $subscription) {
    $this->subscriptions[$subscription->topic->name] = $subscription;
    $subscription->setAsSubscribed(false);
  }

  /**
   * @param \Mqtt\Entity\Subscription $subscription
   */
  public function remove(\Mqtt\Entity\Subscription $subscription) {
    unset($this->subscriptions[$subscription->topic->name]);
  }

  /**
   * @param string $topicName
   * @return \Mqtt\Entity\Subscription[]
   */
  public function getAllByTopicFilter(string $topicName) : array {
    $subscriptions = [];
    foreach ($this->subscriptions as $subscription) {
      if ($subscription->topic->isMatching($topicName)) {
        $subscriptions[] = $subscription;
      }
    }
    return $subscriptions;
  }

  /**
   * @param string $topicName
   * @return \Mqtt\Entity\Subscription[]
   */
  public function getAllUnsubscribed() : array {
    $subscriptions = [];
    foreach ($this->subscriptions as $subscription) {
      if (!$subscription->isSubscribed()) {
        $subscriptions[] = $subscription;
      }
    }
    return $subscriptions;
  }

}
