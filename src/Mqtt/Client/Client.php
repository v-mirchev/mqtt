<?php declare(ticks = 1);

namespace Mqtt\Client;

class Client implements \Mqtt\Session\IHandler, \Mqtt\Client\IClient {

  /**
   * @var \Mqtt\Session\ISession
   */
  protected $session;

  /**
   * @var \Mqtt\IConsumer
   */
  protected $consumer;

  /**
   * @var \Mqtt\Client\Subscriptions
   */
  protected $subscriptions;

  /**
   * @var \Mqtt\Client\Messages
   */
  protected $messages;

  /**
   * @param \Mqtt\Session\ISession $session
   * @param \Mqtt\Client\Subscriptions $subscriptions
   * @param \Mqtt\Client\Messages $messages
   */
  public function __construct(
    \Mqtt\Session\ISession $session,
    \Mqtt\Client\Subscriptions $subscriptions,
    \Mqtt\Client\Messages $messages
  ) {
    $this->session = $session;
    $this->session->setClient($this);

    $this->subscriptions = $subscriptions;
    $this->messages = $messages;

    $this->consumer = new class implements \Mqtt\IConsumer {
      public function onStart(Client $client): void {}
      public function onStop(Client $client): void {}
      public function onTick(Client $client): void {}
    };

    $this->registerSignalHandlers();
  }

  public function setConsumer(\Mqtt\IConsumer $consumer) : \Mqtt\Client\Client {
    $this->consumer = $consumer;
    return $this;
  }

  public function start() : void {
    $this->session->start();
  }

  public function stop() : void {
    $this->session->stop();
  }

  /**
   * @return \Mqtt\Entity\Message
   */
  public function message() : \Mqtt\Entity\Message {
    $message = $this->messages->create();
    $this->messages->add($message);
    return $message;
  }

  public function publish() : void {
    foreach ($this->messages as $message) {
      $this->session->publish($message);
      $this->messages->remove($message);
    }
  }

  public function subscribe() : void {
    $this->session->subscribe($this->subscriptions->getAllUnsubscribed());
  }

  /**
   * @return \Mqtt\Entity\Subscription
   */
  public function subscription() : \Mqtt\Entity\Subscription {
    $subscription = $this->subscriptions->create();
    $this->subscriptions->add($subscription);
    return $subscription;
  }

  /**
   * @param \Mqtt\Entity\Subscription $subscription
   * @return \Mqtt\Client\IClient
   */
  public function unsubscription(\Mqtt\Entity\Subscription $subscription) : \Mqtt\Client\IClient {
    if ($subscription->isSubscribed()) {
      $subscription->setAsSubscribed(false);
    }
    return $this;
  }

  public function unsubscribe(): void {
    $this->session->unsubscribe($this->subscriptions->getAllUnsubscribed());
  }

  public function onTick(): void {
    $this->consumer->onTick($this);
  }

  public function onConnect(): void {
    $this->consumer->onStart($this);
  }

  public function onDisconnect(): void {
    $this->consumer->onStop($this);
  }

  public function registerSignalHandlers() {
    pcntl_signal(SIGINT, function() {
      $this->stop();
    });

    pcntl_signal(SIGTERM, function() {
      $this->stop();
    });
  }

}
