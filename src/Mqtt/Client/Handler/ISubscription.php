<?php declare(strict_types = 1);

namespace Mqtt\Client\Handler;

interface ISubscription {

  /**
   * @param \Mqtt\Entity\TopicFilter $topicFilter
   * @return void
   */
  public function onSubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topicFilter) : void;

  /**
   * @param \Mqtt\Entity\TopicFilter $topicFilter
   * @return void
   */
  public function onSubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topicFilter) : void;

  /**
   * @param \Mqtt\Entity\TopicFilter $topicFilter
   * @return void
   */
  public function onUnsubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topicFilter) : void;

  /**
   * @param \Mqtt\Entity\TopicFilter $topicFilter
   * @return void
   */
  public function onUnsubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topicFilter) : void;

  /**
   * @param \Mqtt\Entity\TopicFilter $topicFilter
   * @return void
   */
  public function onSubscribeFailed(\Mqtt\Entity\TopicFilter $topicFilter) : void;

  /**
   * @param \Mqtt\Entity\TopicFilter $topicFilter
   * @return void
   */
  public function onSubscribed(\Mqtt\Entity\TopicFilter $topicFilter) : void;

  /**
   * @param \Mqtt\Entity\Message
   * @return void
   */
  public function onMessage(\Mqtt\Entity\Message $message) : void;

}
