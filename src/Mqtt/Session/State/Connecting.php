<?php

namespace Mqtt\Session\State;

class Connecting implements \Mqtt\Session\State\IState, \Mqtt\ITimeoutHandler {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  /**
   * @var \Mqtt\Timeout
   */
  protected $timeout;

  /**
   * @param \Mqtt\Timeout $timeout
   */
  public function __construct(\Mqtt\Timeout $timeout) {
    $this->timeout = $timeout;
  }

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    $this->context->getProtocol()->disconnect();
  }

  public function publish() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function onTick() : void {
    $this->timeout->tick();
  }

  public function onProtocolConnect() : void {
    $this->timeout->stop();
    $this->stateChanger->setState(\Mqtt\Session\State\IState::CONNECTED);
  }

  public function onProtocolDisconnect() : void {
    $this->timeout->stop();
    $this->stateChanger->setState(\Mqtt\Session\State\IState::DISCONNECTED);
  }

  public function onStateEnter() : void {
    $this->timeout->subscribe($this);
    $this->timeout->setInterval(5);
    $this->timeout->start();
    $this->context->getProtocol()->connect();
  }

  public function __destruct() {
    $this->timeout->stop();
    unset($this->timeout);
  }

  public function onTimeout(): void {
    throw new Exception('Could not connect');
  }

}
