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
   * @var \Mqtt\Entity\Subsription[]
   */
  protected $subscriptions;

  /**
   * @param \Mqtt\Session\ISession $session
   */
  public function __construct(\Mqtt\Session\ISession $session) {
    $this->session = $session;
    $this->session->setClient($this);

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

  public function publish() : void {
    $this->session->publish();
  }

  public function subscribe() : void {
    $this->session->subscribe($this->subscriptions);
  }

  public function subscription() : \Mqtt\Entity\Subsription {
    $this->subscriptions[] = new \Mqtt\Entity\Subsription(new \Mqtt\Entity\Topic(new \Mqtt\Entity\QoS()));
    return end($this->subscriptions);
  }

  public function unsubscribe(): void {
    $this->session->unsubscribe();
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
