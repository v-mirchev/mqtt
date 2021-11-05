<?php

namespace Mqtt\Protocol\Decoder\Frame;

interface IDecoder extends \Mqtt\Protocol\Decoder\Frame\IStreamDecoder {

  /**
   * @param callable $onFrameCompleted
   */
  public function onCompleted(callable $onFrameCompleted) : void;
}
