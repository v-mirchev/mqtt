<?php

namespace Mqtt\Client\Handler;

interface ISubscription {

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onSubscribeAcknowledged(\Mqtt\Entity\Topic $topic) : void;

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onSubscribeUnacknowledged(\Mqtt\Entity\Topic $topic) : void;

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onUnsubscribeUnacknowledged(\Mqtt\Entity\Topic $topic) : void;

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onUnsubscribeAcknowledged(\Mqtt\Entity\Topic $topic) : void;

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onSubscribeFailed(\Mqtt\Entity\Topic $topic) : void;

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onSubscribed(\Mqtt\Entity\Topic $topic) : void;

}
