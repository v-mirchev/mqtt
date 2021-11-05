<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet;

interface IDecoder extends \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onCompleted(callable $onPacketComplete) : void;

}
