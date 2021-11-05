<?php

namespace Mqtt\Protocol\Encoder\Frame;

interface IEncoder {

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function encode(\Mqtt\Protocol\Entity\Frame $frame) : void;

  /**
   * @param callable $onPacketCompleted
   */
  public function onCompleted(callable $onPacketCompleted) : void;

}
