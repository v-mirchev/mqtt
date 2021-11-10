<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame\Fsm\State;

trait TState {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Fsm\Context
   */
  protected $context;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Fsm\IActions
   */
  protected $action;

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\Fsm\Context $context
   */
  public function setContext(\Mqtt\Protocol\Decoder\Frame\Fsm\Context $context) {
    $this->context = $context;
  }

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\Fsm\IActions $action
   */
  public function setActions(\Mqtt\Protocol\Decoder\Frame\Fsm\IActions $action) {
    $this->action = $action;
  }

}
