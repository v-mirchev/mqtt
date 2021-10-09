<?php

namespace Mqtt\Session\State\Connection;

trait TSession  {

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function publish() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function unsubscribe() : void {
    throw new \Exception('Not allowed in this state');
  }

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
