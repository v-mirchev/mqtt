<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Frame;

interface IStreamEncoder {

  public function set($value) : void;

  public function encode(\Mqtt\Protocol\Binary\IBuffer $buffer) : void;

}
