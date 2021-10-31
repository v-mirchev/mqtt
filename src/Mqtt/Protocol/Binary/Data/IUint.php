<?php

namespace Mqtt\Protocol\Binary\Data;

interface IUint {

  /**
   * @param int|\Mqtt\Protocol\Binary\Data $value
   * @return $this
   */
  public function set($value) : \Mqtt\Protocol\Binary\Data\IUint;

  /**
   * @return int
   */
  public function get(): int;

  /**
   * @return int
   */
  public function bits(): \Mqtt\Protocol\Binary\Data\Bit;

  /**
   * @return string
   */
  public function __toString() : string;

}
