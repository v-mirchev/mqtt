<?php

namespace Test\Helpers;

trait TestPassedAssert {

  public function pass() {
    $this->assertTrue(true);
  }

  public function assertNoExceptionThrown() {
    $this->assertTrue(true);
  }

}
