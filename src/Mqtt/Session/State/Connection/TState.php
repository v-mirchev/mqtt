<?php

namespace Mqtt\Session\State\Connection;

trait TState  {

  /**
   * @var \Mqtt\Session\ISessionStateChanger
   */
  protected $stateChanger;

  /**
   * @var \Mqtt\Session\ISessionContext
   */
  protected $context;

  public function onStateEnter() : void {}

  /**
   * @param \Mqtt\Session\ISessionStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Session\ISessionStateChanger $stateChanger) : void {
    $this->stateChanger = $stateChanger;
  }

  /**
   * @param \Mqtt\Session\ISessionContext $context
   * @return void
   */
  public function setContext(\Mqtt\Session\ISessionContext $context) : void {
    $this->context = $context;
  }

}
