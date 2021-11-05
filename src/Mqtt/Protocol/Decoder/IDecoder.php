<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder;

interface IDecoder  {

  /**
   * @param string|null $chars
   * @return void
   */
  public function decode(string $chars = null) : void;

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onDecodingCompleted(callable $onPacketComplete) : void;

}
