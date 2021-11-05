<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Connection\State;

class Disconnected implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

}
