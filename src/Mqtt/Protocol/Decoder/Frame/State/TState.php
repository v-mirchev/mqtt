<?php

namespace Mqtt\Protocol\Decoder\Frame\State;

trait TState {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\FrameStateContext
   */
  protected $context;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\IFrameStateAction
   */
  protected $action;

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\FrameStateContext $context
   */
  public function setContext(\Mqtt\Protocol\Decoder\Frame\FrameStateContext $context) {
    $this->context = $context;
  }

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\IFrameStateAction $action
   */
  public function setAction(\Mqtt\Protocol\Decoder\Frame\IFrameStateAction $action) {
    $this->action = $action;
  }

}
