<?php

namespace Mqtt\Protocol\Decoder\Frame;

trait TReceiver {

  /**
   * @return \Mqtt\Protocol\Decoder\Frame\Receiver
   */
  public function receiver() : \Mqtt\Protocol\Decoder\Frame\Receiver {
    $receiver = new \Mqtt\Protocol\Decoder\Frame\Receiver();
    $receiver($this);
    return $receiver;
  }

}
