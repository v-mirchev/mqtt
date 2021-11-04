<?php

namespace Mqtt\Protocol\Decoder\Frame\State;

interface IState {

  const CONTROL_HEADER_PROCESSING = 'frame.fsm.control.header.processing';
  const REMAINING_LENGTH_PROCESSING = 'frame.fsm.remaining.length.processing';
  const REMAINING_LENGTH_COMPLETED = 'frame.fsm.reminaing.length.completed';
  const PAYLOAD_PROCESSING = 'frame.fsm.paylod.processing';
  const FRAME_COMPLETED = 'frame.fsm.frame.completed';

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\FrameStateContext $context
   */
  public function setContext(\Mqtt\Protocol\Decoder\Frame\FrameStateContext $context);

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\IFrameStateAction $action
   */
  public function setAction(\Mqtt\Protocol\Decoder\Frame\IFrameStateAction $action);

  public function onEnter();

  public function input(string $char);

}
