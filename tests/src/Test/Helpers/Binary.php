<?php declare(strict_types = 1);

namespace Test\Helpers;

trait Binary {

  /**
   * @param string $string
   * @return string
   */
  protected function hex2string(string $string) : string {
    return implode('',
      array_map('chr',
      array_map(function ($value) { return intval($value, 16); }, str_split(
        str_replace(' ', '', $string),
      2)
    )));
  }

}
