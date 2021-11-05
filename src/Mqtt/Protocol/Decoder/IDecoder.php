<?php

namespace Mqtt\Protocol\Decoder;

interface IDecoder  {

  /**
   * @return void
   */
  public function init() : void;

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
