<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\AtMostOnce;

class Publishing implements \Mqtt\Protocol\Packet\Flow\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Protocol\Packet\Flow\TState;

  /**
   * @return void
   */
  public function onStateEnter(): void {
    /* @var $packet \Mqtt\Protocol\Packet\Type\Publish */
    $packet = $this->flowContext->getOutgoingPacket();
    $packet->message->handler->onMessageSent($packet->message);
  }

}
