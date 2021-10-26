<?php

namespace Mqtt\Protocol\Packet\Flow;

trait TState  {

  /**
   * @var \Mqtt\Protocol\Packet\Flow\IStateChanger
   */
  protected $stateChanger;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\ISessionContext
   */
  protected $context;

  public function onStateEnter() : void {}

  /**
   * @param \Mqtt\Protocol\Packet\Flow\IStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Protocol\Packet\Flow\IStateChanger $stateChanger) : void {
    $this->stateChanger = $stateChanger;
  }

  /**
   * @param \Mqtt\Protocol\Packet\Flow\IContext $context
   * @return void
   */
  public function setContext(\Mqtt\Protocol\Packet\Flow\IContext $context) : void {
    $this->context = $context;
  }

}
