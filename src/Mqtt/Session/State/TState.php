<?php

namespace Mqtt\Session\State;

trait TState  {

  /**
   * @var \Mqtt\Session\IStateChanger
   */
  protected $stateChanger;

  /**
   * @var \Mqtt\Session\IContext
   */
  protected $context;

  public function onStateEnter() : void {}

  /**
   * @param \Mqtt\Session\IStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Session\IStateChanger $stateChanger) : void {
    $this->stateChanger = $stateChanger;
  }

  /**
   * @param \Mqtt\Session\IContext $context
   * @return $this
   */
  public function setContext(\Mqtt\Session\IContext $context) {
    $this->context = $context;
    return $this;
  }

}
