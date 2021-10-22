<?php declare(ticks = 1);

namespace Mqtt\Protocol\Packet\Flow\KeepAlive\State;

class Disabled implements \Mqtt\Protocol\Packet\Flow\IState {
  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;


  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::PINGRESP)) {
      throw new \Exception('PONG packet not expected');
    }
  }

}

