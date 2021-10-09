<?php

namespace Mqtt\Session\State\Connection;

trait TSessionKeepAlive  {

  /**
   * @param \Mqtt\Session\ISessionKeepAliveContext $context
   * @return void
   */
  public function setKeepAliveContext(\Mqtt\Session\ISessionKeepAliveContext $context) : void {
    $this->keepAliveContext = $context;
  }

  /**
   * @param \Mqtt\Session\ISessionKeepAliveContext $context
   * @return void
   */
  public function setKeepAliveStateChanger(\Mqtt\Session\ISessionKeepAlive $context) : void {
    $this->keepAliveContext = $context;
  }

}
