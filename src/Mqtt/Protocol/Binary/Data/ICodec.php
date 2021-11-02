<?php

namespace Mqtt\Protocol\Binary\Data;

interface ICodec {

  public function decode(\Mqtt\Protocol\Binary\Data\IBuffer $buffer) : void;

  public function encode(\Mqtt\Protocol\Binary\Data\IBuffer $buffer) : void;

}
