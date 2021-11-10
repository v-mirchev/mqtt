<?php declare(strict_types = 1);

namespace Mqtt\Session;

interface ISession  {

  /**
   * @param \Mqtt\Client\IClient $client
   * @return void
   */
  public function setClient(\Mqtt\Client\IClient $client): void;

  public function start() : void;

  public function stop() : void;

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function publish(\Mqtt\Entity\Message $message) : void;

  /**
   * @param \Mqtt\Entity\Subscription[] $subscriptions
   * @return void
   */
  public function subscribe(array $subscriptions) : void;

  /**
   * @param \Mqtt\Entity\Subscription[] $subscriptions
   * @return void
   */
  public function unsubscribe(array $subscriptions) : void;

}
