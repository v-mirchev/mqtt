<?php

namespace Mqtt\Protocol\Decoder;

interface IDecoder extends \Mqtt\Protocol\Decoder\IPacketDecoder {

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onCompleted(callable $onPacketComplete) : void;

}
