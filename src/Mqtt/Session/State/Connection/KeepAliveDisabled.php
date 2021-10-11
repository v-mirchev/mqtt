<?php declare(ticks = 1);

namespace Mqtt\Session\State\Connection;

class KeepAliveDisabled implements \Mqtt\Session\State\ISessionState {
  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\Connection\TState;
}
