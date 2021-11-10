<?php declare(strict_types = 1);

namespace Test\Helpers;

trait CallSequenceAssert {

  /**
   * @var CallSequence
   */
  protected $instance;

  public function callSequence() : CallSequence {
    if (empty($this->instance)) {
      $this->instance = new CallSequence($this);
    }
    return $this->instance;
  }

}
