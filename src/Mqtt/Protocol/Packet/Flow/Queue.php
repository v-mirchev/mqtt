<?php

namespace Mqtt\Protocol\Packet\Flow;

class Queue implements \Mqtt\Protocol\Packet\Flow\IQueue {

  /**
   * @var \Mqtt\Session\ISession[]
   */
  protected $queue;

  public function __construct() {
    $this->queue = [];
  }

  public function _clone() {
    $this->queue = [];
  }

  public function reset() : void {
    $this->queue = [];
  }

  /**
   * @param int $id
   * @param \Mqtt\Session\ISession $flow
   */
  public function add(int $id, \Mqtt\Session\ISession $flow) : void {
    if (isset($this->queue[$id])) {
      throw new \Exception('Queue ID <' . $id . '> already exists');
    }
    $this->queue[$id] = $flow;
  }

  /**
   * @param int $id
   */
  public function remove(int $id) : void {
    if (!isset($this->queue[$id])) {
      throw new \Exception('Queue ID <' . $id . '> does not exist');
    }
    unset($this->queue[$id]);
  }

  /**
   * @param int $id
   * @return \Mqtt\Session\ISession
   */
  public function get(int $id): \Mqtt\Session\ISession {
    if (!isset($this->queue[$id])) {
      throw new \Exception('Queue ID <' . $id . '> does not exist');
    }
    return $this->queue[$id];
  }

  /**
   * @return \Traversable
   */
  public function getIterator(): \Traversable {
    return new \ArrayIterator($this->queue);
  }

}
