<?php

namespace Mqtt\Protocol\Packet\Flow\Connection\State;

class Disconnected implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  public function start() : void {
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

  public function onProtocolConnect(): void {
  }

}
