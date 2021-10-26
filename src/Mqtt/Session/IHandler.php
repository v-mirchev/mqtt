<?php

namespace Mqtt\Session;

interface IHandler {

  /**
   * @return void
   */
  public function onConnect() : void;

  /**
   * @return void
   */
  public function onDisconnect() : void;

  /**
   * @return void
   */
  public function onTick() : void;

}
