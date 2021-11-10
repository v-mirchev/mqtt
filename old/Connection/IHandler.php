<?php declare(strict_types = 1);

namespace Mqtt\Connection;

interface IHandler {

  /**
   * @return void
   */
  public function onConnectionConnect() : void;

  /**
   * @return void
   */
  public function onConnectionDisconnect() : void;

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
