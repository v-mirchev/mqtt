<?php

namespace Mqtt\Client\Handler;

interface IMessage {

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function onMessageSent(\Mqtt\Entity\Message $message) : void;

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function onMessageAcknowledged(\Mqtt\Entity\Message $message) : void;

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function onMessageUnacknowledged(\Mqtt\Entity\Message $message) : void;

}
