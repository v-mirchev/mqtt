<?php

namespace Mqtt\Protocol\Packet\Flow;

interface IQueue extends \IteratorAggregate {

  /**
   * @param int $id
   * @param \Mqtt\Session\ISession $flow
   */
  public function add(int $id, \Mqtt\Session\ISession $flow) : void;

  /**
   * @param int $id
   * @return \Mqtt\Session\ISession
   */
  public function get(int $id) : \Mqtt\Session\ISession;

  /**
   * @param int $id
   */
  public function remove(int $id) : void;

  public function reset() : void;

}
