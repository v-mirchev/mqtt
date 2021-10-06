<?php

namespace Mqtt\Connection;

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

  /**
   * @param \Iterator $stream
   * @return void
   */
  public function read(\Iterator $stream) : void;

}
