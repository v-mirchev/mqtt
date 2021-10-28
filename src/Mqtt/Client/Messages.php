<?php

namespace Mqtt\Client;

class Messages {

  /**
   * @var \Mqtt\Entity\Message[]
   */
  protected $messages;

  /**
   * @var \Mqtt\Entity\Message
   */
  protected $messagePrototype;

  /**
   * @param \Mqtt\Entity\Message $messagePrototype
   */
  public function __construct(\Mqtt\Entity\Message $messagePrototype) {
    $this->messagePrototype = clone $messagePrototype;
  }

  /**
   * @return \Mqtt\Entity\Message $message
   */
  public function create() : \Mqtt\Entity\Message {
    return clone $this->messagePrototype;
  }

  /**
   * @param \Mqtt\Entity\Message $message
   */
  public function add(\Mqtt\Entity\Message $message) {
    $this->messages[] = $message;
    $message->setAsSubscribed(false);
  }

  /**
   * @param \Mqtt\Entity\Message $message
   */
  public function remove(\Mqtt\Entity\Message $message) {
    unset($this->messages[0]);
  }

}
