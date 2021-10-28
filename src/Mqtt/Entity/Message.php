<?php

namespace Mqtt\Entity;

class Message implements \Mqtt\Entity\IQoS {

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
   * @var bool
   */
  public $isDup;

  /**
   * @var  \Mqtt\Client\Handler\IMessage
   */
  public $handler;

  /**
   * @param \Mqtt\Entity\QoS $qos
   */
  public function __construct(\Mqtt\Entity\QoS $qos) {
    $this->qos = clone $qos;
    $this->qos->setRelated($this);

    $this->isRetain = false;
    $this->isDup = false;
    $this->content = '';
    $this->topic = '';
    $this->handler = new class implements \Mqtt\Client\Handler\IMessage {
      public function onMessageAcknowledged(Message $message): void {}
      public function onMessageSent(Message $message): void {}
      public function onMessageUnacknowledged(Message $message): void {}
    };
  }

  public function __clone() {
    $this->qos = clone $this->qos;
    $this->qos->setRelated($this);

    $this->isRetain = false;
    $this->isDup = false;
    $this->content = '';
    $this->topic = '';
    $this->handler = new class implements \Mqtt\Client\Handler\IMessage {
      public function onMessageAcknowledged(Message $message): void {}
      public function onMessageSent(Message $message): void {}
      public function onMessageUnacknowledged(Message $message): void {}
    };
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

  /**
   * @param bool $isDup
   * @return \Mqtt\Entity\Message
   */
  public function dup(bool $isDup = true) : \Mqtt\Entity\Message {
    $this->isDup = $isDup;
    return $this;
  }

  /**
   * @param \Mqtt\Client\Handler\IMessage $handler
   * @return \Mqtt\Entity\Message
   */
  public function handler(\Mqtt\Client\Handler\IMessage $handler) : \Mqtt\Entity\Message {
    $this->handler = $handler;
    return $this;
  }

}
