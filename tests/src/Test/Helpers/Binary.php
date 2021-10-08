<?php

namespace Test\Helpers;

trait Binary {

  /**
   * @return string
   */
  protected function toStringStream() : string {
    if (is_array(func_get_arg(0))) {
      $bytes = func_get_arg(0);
    } else {
      $bytes = func_get_args();
    }
    return implode('', array_map('chr', $bytes));
  }

  /**
   * @param string $string
   * @return string
   */
  protected function randomStringStream(int $length = 8) : string {
    return implode('',
      array_map('chr',
      array_map(function ($value) { return \rand(0, 65535); }, array_fill(0, $length, 1)
    )));
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
    if (is_array(func_get_arg(0))) {
      $bytes = func_get_arg(0);
    } else {
      $bytes = func_get_args();
    }
    return new \ArrayIterator(array_map('chr', $bytes));
  }

}
