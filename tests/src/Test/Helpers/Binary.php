<?php

namespace Test\Helpers;

trait Binary {

  protected function toStringStream() : string {
    return implode('', array_map('chr', func_get_args()));
  }

  protected function toArrayStream() : \Iterator {
    return new \ArrayIterator(array_map('chr', func_get_args()));
  }

}
