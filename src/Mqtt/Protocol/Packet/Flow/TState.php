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

  /**
   * @var \Mqtt\Protocol\Packet\Flow\IFlowContext
   */
  protected $flowContext;

  public function onStateEnter() : void {}

  /**
   * @param \Mqtt\Protocol\Packet\Flow\IStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Protocol\Packet\Flow\IStateChanger $stateChanger) : void {
    $this->stateChanger = $stateChanger;
  }

  /**
   * @param \Mqtt\Protocol\Packet\Flow\ISessionContext $context
   * @return void
   */
  public function setContext(\Mqtt\Protocol\Packet\Flow\ISessionContext $context) : void {
    $this->context = $context;
  }


  /**
   * @param \Mqtt\Protocol\Packet\Flow\IFlowContext $flowContext
   * @return void
   */
  public function setSubcontext(\Mqtt\Protocol\Packet\Flow\IFlowContext $flowContext) : void {
    $this->flowContext = $flowContext;
  }
}
