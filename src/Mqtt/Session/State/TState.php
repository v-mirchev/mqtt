<?php

namespace Mqtt\Session\State;

trait TState  {

  /**
   * @var \Mqtt\Session\IStateChanger
   */
  protected $stateChanger;

  /**
   * @var \Mqtt\Protocol\Protocol
   */
  protected $protocol;

  public function onStateEnter() : void {}

  /**
   * @param \Mqtt\Session\IStateChanger $stateChanger
   * @return void
   */
  public function setStateChanger(\Mqtt\Session\IStateChanger $stateChanger) : void {
    $this->stateChanger = $stateChanger;
  }

  /**
   * @param \Mqtt\Protocol\Protocol $protocol
   * @return $this
   */
  public function setProtocol(\Mqtt\Protocol\Protocol $protocol) {
    $this->protocol = $protocol;
    return $this;
  }

}
