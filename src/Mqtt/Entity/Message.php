<?php

namespace Mqtt\Entity;

class Message implements \Mqtt\Entity\IQoS {

  /**
   * @var int
   */
  public $id;

  /**
   * @var \Mqtt\Entity\QoS
   */
  public $qos;

  /**
   * @var string
   */
  public $topic;

  /**
   * @var string
   */
  public $content;

  /**
   * @var bool
   */
  public $isRetain;

  /**
   * @param \Mqtt\Entity\QoS $qos
   */
  public function __construct(\Mqtt\Entity\QoS $qos) {
    $this->qos = $qos;
    $this->qos->setRelated($this);
  }

  /**
   * @return \Mqtt\Entity\Message
   */
  public function atMostOnce() : \Mqtt\Entity\Message  {
    return $this->qos->atMostOnce();
  }

  /**
   * @return \Mqtt\Entity\Message
   */
  public function atLeastOnce() : \Mqtt\Entity\Message {
    return $this->qos->atLeastOnce();
  }

  /**
   * @return \Mqtt\Entity\Message
   */
  public function exactlyOnce() : \Mqtt\Entity\Message {
    return $this->qos->exactlyOnce();
  }

  /**
   * @param string $topic
   * @return \Mqtt\Entity\Message
   */
  public function topic(string $topic) : \Mqtt\Entity\Message {
    $this->topic = $topic;
    return $this;
  }

  /**
   * @param string $content
   * @return \Mqtt\Entity\Message
   */
  public function content(string $content) : \Mqtt\Entity\Message {
    $this->content = $content;
    return $this;
  }

  /**
   * @param bool $isRetain
   * @return \Mqtt\Entity\Message
   */
  public function retain(bool $isRetain = true) : \Mqtt\Entity\Message {
    $this->isRetain = $isRetain;
    return $this;
  }

}
