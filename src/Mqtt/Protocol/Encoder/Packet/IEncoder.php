<?php

namespace Mqtt\Protocol\Encoder\Packet;

interface IEncoder extends \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder{

  /**
   * @param callable $onPacketCompleted
   */
  public function onCompleted(callable $onPacketCompleted) : void;

}
