<?php

namespace Test\Helpers;

trait TestPassedAssert {

  /**
   * @var CallSequence
   */
  protected $instance;

  public function pass() {
    $this->assertTrue(true);
  }

  public function assertNoExceptionThrown() {
    $this->assertTrue(true);
  }

}
