<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame\Fsm;

interface IActions {


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
