<?php declare(ticks = 1);

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
   */
  public function subscribe(\Mqtt\ITimeoutHandler $handler) {
    $this->handler = $handler;
  }

  /**
   * @param int $interval
   * @return $this
   */
  public function setInterval(int $interval) {
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

    if ($timePassed > $this->interval) {
      $this->handler->onTimeout();
    }
  }

}
