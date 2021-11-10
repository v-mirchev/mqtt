<?php declare(strict_types = 1);

namespace Mqtt\Connection;

interface IConnection {

  /**
   * @param \Mqtt\Connection\IHandler $protocol
   * @return $this
   */
  public function setProtocol(\Mqtt\Connection\IHandler $protocol) : void;

  /**
   * @return \Iterator
   */
  public function stream() : \Iterator;


  /**
   * @return void
   */
  public function establish() : void;

  /**
   * @return void
   */
  public function disconnect() : void;

  /**
   * @param string $bytes
   * @return void
   */
  public function write(string $bytes) : void;

}
