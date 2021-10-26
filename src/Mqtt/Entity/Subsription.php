<?php

namespace Mqtt\Entity;

class Subsription implements IQoS {

  /**
   * @var \Mqtt\Entity\Topic
   */
  public $topic;

  /**
   * @var  \Mqtt\Client\Handler\ISubscription
   */
  public $handler;

  public function __construct(\Mqtt\Entity\Topic $topic) {
    $this->handler = new class implements \Mqtt\Client\Handler\ISubscription {
      public function onAcknowledged(\Mqtt\Entity\Topic $topic): void {}
      public function onFailed(\Mqtt\Entity\Topic $topic): void {}
      public function onSubscribed(\Mqtt\Entity\Topic $topic): void {}
      public function onUnacknowledged(\Mqtt\Entity\Topic $topic): void {}
    };
    $this->topic = clone $topic;
    $this->topic->qos->setRelated($this);
  }

  /**
   * @param string $name
   * @return \Mqtt\Entity\Subsription
   */
  public function topic(string $name) : \Mqtt\Entity\Subsription {
    $this->topic->name($name);
    return $this;
  }

  /**
   * @return \Mqtt\Entity\Subsription
   */
  public function atMostOnce() : \Mqtt\Entity\Subsription {
    return $this->topic->qos->atMostOnce();
  }

  /**
   * @return \Mqtt\Entity\Subsription
   */
  public function atLeastOnce() : \Mqtt\Entity\Subsription {
    return $this->topic->qos->atLeastOnce();
  }

  /**
   * @return \Mqtt\Entity\Subsription
   */
  public function exactlyOnce() : \Mqtt\Entity\Subsription {
    return $this->topic->qos->exactlyOnce();
  }

  /**
   * @param \Mqtt\Client\Handler\ISubscription $handler
   * @return \Mqtt\Entity\Subsription
   */
  public function handler(\Mqtt\Client\Handler\ISubscription $handler) : \Mqtt\Entity\Subsription {
    $this->handler = $handler;
    return $this;
  }

}
