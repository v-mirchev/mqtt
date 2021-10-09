<?php

namespace Mqtt\Session;

interface ISessionKeepAliveContext {

  /**
   * @return \Mqtt\Timeout
   */
  public function getTimeoutWatcher() : \Mqtt\Timeout;

}
