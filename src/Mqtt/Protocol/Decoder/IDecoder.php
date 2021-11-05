<?php

namespace Mqtt\Protocol\Decoder;

interface IDecoder  {

  /**
   * @param string|null $chars
   * @return void
   */
  public function input(string $chars = null) : void;

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onCompleted(callable $onPacketComplete) : void;

}
