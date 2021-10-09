<?php

namespace Mqtt\Session\State\Connection;

trait TSession  {

  /**
   * @var \Mqtt\Session\ISessionStateChanger
   */
  protected $stateChanger;

  /**
   * @var \Mqtt\Session\ISessionContext
   */
  protected $context;

  public function start() : void {}

  public function stop() : void {}

  public function publish() : void {}

  public function subscribe() : void {}

  public function unsubscribe() : void {}

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
