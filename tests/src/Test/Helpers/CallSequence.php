<?php

namespace Test\Helpers;

class CallSequence {

  /**
   * @var \PHPUnit\Framework\TestCase
   */
  protected $assert;

  /**
   * @var int
   */
  protected $callIndex;

  /**
   * @param \PHPUnit\Framework\Assert $assert
   */
  public function __construct(\PHPUnit\Framework\Assert $assert) {
    $this->assert = $assert;
    $this->callIndex = 0;
  }

  public function start() : void {
    $this->callIndex = 0;
  }

  /**
   * @return \PHPUnit\Framework\MockObject\Matcher\InvokedAtIndex
   */
  public function next() : \PHPUnit\Framework\MockObject\Matcher\InvokedAtIndex {
    $matcher = $this->assert->at($this->callIndex);
    $this->callIndex ++;
    return $matcher;
  }

}