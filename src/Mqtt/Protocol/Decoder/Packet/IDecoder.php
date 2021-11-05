<?php

namespace Mqtt\Protocol\Decoder\Packet;

interface IDecoder extends \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onCompleted(callable $onPacketComplete) : void;

}
