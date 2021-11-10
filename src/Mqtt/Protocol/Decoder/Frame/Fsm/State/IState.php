<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame\Fsm\State;

interface IState {

  const CONTROL_HEADER_PROCESSING = 'frame.fsm.control.header.processing';
  const REMAINING_LENGTH_PROCESSING = 'frame.fsm.remaining.length.processing';
  const REMAINING_LENGTH_COMPLETED = 'frame.fsm.reminaing.length.completed';
  const PAYLOAD_PROCESSING = 'frame.fsm.paylod.processing';
  const FRAME_COMPLETED = 'frame.fsm.frame.completed';

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\Fsm\Context $context
   */
  public function setContext(\Mqtt\Protocol\Decoder\Frame\Fsm\Context $context);

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\Fsm\IActions $action
   */
  public function setActions(\Mqtt\Protocol\Decoder\Frame\Fsm\IActions $action);

  public function onEnter();

  public function input(string $char);

}
