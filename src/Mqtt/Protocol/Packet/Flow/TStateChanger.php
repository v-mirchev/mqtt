<?php

namespace Mqtt\Protocol\Packet\Flow;

trait TStateChanger  {

  /**
   * @param string $sessionState
   * @return void
   */
  public function setState(string $sessionState) : void {
    error_log('FLOW::' . $sessionState);
    $previous = $this->flowState;
    $this->flowState = $this->stateFactory->create($sessionState);
    $this->flowState->setStateChanger($this);
    $this->flowState->onStateEnter();
    unset($previous);
  }

}
