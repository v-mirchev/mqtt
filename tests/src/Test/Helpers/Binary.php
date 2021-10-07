<?php

namespace Test\Helpers;

trait Binary {

  /**
   * @return string
   */
  protected function toStringStream() : string {
    return implode('', array_map('chr', func_get_args()));
  }

  /**
   * @param string $string
   * @return string
   */
  protected function stringToStringStream(string $string) : string {
    return implode('',
      array_map('chr',
      array_map(function ($value) { return intval($value, 16); }, str_split($string, 2)
    )));
  }

  /**
   * @return \Iterator
   */
  protected function toArrayStream() : \Iterator {
    return new \ArrayIterator(array_map('chr', func_get_args()));
  }

}
