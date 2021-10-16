<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class Connecting implements \Mqtt\Session\State\ISessionState, \Mqtt\ITimeoutHandler {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\Connection\TState;

  /**
   * @var \Mqtt\Timeout
   */
  protected $timeout;

  /**
   * @param \Mqtt\Timeout $timeout
   */
  public function __construct(\Mqtt\Timeout $timeout) {
    $this->timeout = clone $timeout;
  }

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function publish() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function unsubscribe() : void {
    throw new \Exception('Not allowed in this state');
  }

  /**
   * @return void
   */
  public function onStateEnter(): void {
    $this->timeout->
      subscribe($this)->
      setInterval($this->context->getSessionConfiguration()->connectAcknowledgeTimeout)->
      start();
  }

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if (!$packet->is(\Mqtt\Protocol\Packet\IType::CONNACK)) {
      throw new \Exception('CONNACK packet expected');
    }

    if ($packet->getReturnCode() !== 0) {
      throw new \Exception($packet->getReturnCodeMessage() .', error code ' . $packet->getReturnCode());
    }

    $this->stateChanger->setState(\Mqtt\Session\State\ISessionState::CONNECTED);
  }

  public function onTick() : void {
    $this->timeout->tick();
  }

  public function onTimeout(): void {
    throw new \Exception('Connection acknowledge timed out');
  }

  public function __destruct() {
    $this->timeout->stop();
  }

}
