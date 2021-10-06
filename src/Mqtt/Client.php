<?php declare(ticks = 1);

namespace Mqtt;

class Client {

  /**
   * @var \Mqtt\Session\ISession
   */
  protected $session;

  /**
   * @param \Mqtt\Session\ISession $session
   */
  public function __construct(\Mqtt\Session\ISession $session) {
    $this->session = $session;
    $this->registerSignalHandlers();
  }

  public function start() {
    $this->session->start();
  }

  public function stop() : void {
    $this->session->stop();
  }

  public function publish() : void {
    $this->session->publish();
  }

  public function subscribe() : void {
    $this->session->subscribe();
  }

  public function unsubscribe(): void {
    $this->session->unsubscribe();
  }

  public function onTick(): void {
  }

  public function onConnect(): void {
  }

  public function onDisconnect(): void {
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
