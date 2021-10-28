<?php declare(ticks = 1);

namespace Mqtt\Client;

interface IClient {

  public function setConsumer(\Mqtt\IConsumer $consumer) : \Mqtt\Client\IClient;

  public function start() : void;

  public function stop() : void;

  public function publish() : void;

  public function message() : \Mqtt\Entity\Message;

  public function subscribe() : void;

  public function subscription() : \Mqtt\Entity\Subscription;

  public function unsubscribe(): void;

}
