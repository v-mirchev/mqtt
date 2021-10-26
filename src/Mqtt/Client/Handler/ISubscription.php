<?php

namespace Mqtt\Client\Handler;

interface ISubscription {

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onAcknowledged(\Mqtt\Entity\Topic $topic) : void;

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onUnacknowledged(\Mqtt\Entity\Topic $topic) : void;

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onFailed(\Mqtt\Entity\Topic $topic) : void;

  /**
   * @param \Mqtt\Entity\Topic $topic
   * @return void
   */
  public function onSubscribed(\Mqtt\Entity\Topic $topic) : void;

}
