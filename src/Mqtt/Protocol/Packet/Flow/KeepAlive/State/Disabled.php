<?php

namespace Mqtt\Protocol\Packet\Flow\KeepAlive\State;

class Disabled implements \Mqtt\Protocol\Packet\Flow\IState {
  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;


  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacketType::PINGRESP)) {
      throw new \Exception('PONG packet not expected');
    }
  }

}

