<?php

namespace Mqtt\Session;

interface ISession extends \Mqtt\Protocol\IHandler  {

  /**
   * @param \Mqtt\Client\IClient $client
   * @return void
   */
  public function setClient(\Mqtt\Client\IClient $client): void;

  public function start() : void;

  public function stop() : void;

  public function publish() : void;

  public function subscribe(array $subscriptions) : void;

  public function unsubscribe(array $subscriptions) : void;

}
