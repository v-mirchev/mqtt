<?php

namespace Mqtt\Protocol\Decoder\Frame;

interface IFrameStateAction {


  /**
   * @param callable $onFrameCompleted
   */
  public function onCompleted(callable $onFrameCompleted) : void;

  /**
   * @param string $state
   * @return void
   */
  public function setState(string $state) : void;

}
