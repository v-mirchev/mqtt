<?php declare(strict_types = 1);

namespace Mqtt;

class Timeout  {

  /**
   * @var int
   */
  protected $interval;

  /**
   * @var \Mqtt\ITimeoutHandler
   */
  protected $handler;

  /**
   * @var int
   */
  protected $time;

  /**
   * @var bool
   */
  protected $isActive;

  public function __construct() {
    $this->time = null;
    $this->isActive = false;
  }

  /**
   * @param \Mqtt\ITimeoutHandler $handler
   * @return $this
   */
  public function subscribe(\Mqtt\ITimeoutHandler $handler): \Mqtt\Timeout {
    $this->handler = $handler;
    return $this;
  }

  /**
   * @param int $interval
   * @return $this
   */
  public function setInterval(int $interval) : \Mqtt\Timeout {
    $this->interval = $interval;
    return $this;
  }

  public function reset(): void {
    $this->time = time();
  }

  public function start(): void {
    $this->reset();
    $this->isActive = true;
  }

  public function stop(): void {
    $this->time = null;
    $this->isActive = false;
  }

  public function tick(): void {
    if (!$this->isActive) {
      return;
    }

    $timePassed = time() - $this->time;

    if ($timePassed >= $this->interval) {
      $this->handler->onTimeout();
    }
  }

  public function __clone() {
    $this->handler = null;
    $this->interval = null;
    $this->isActive = null;
    $this->time = null;
  }

}