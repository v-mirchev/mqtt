<?php

namespace Mqtt\Protocol\Binary\Data;

interface ICodec {

  public function decode(\Mqtt\Protocol\Binary\IBuffer $buffer) : void;

  public function encode(\Mqtt\Protocol\Binary\IBuffer $buffer) : void;

}
