<?php

namespace Mqtt\Protocol\Decoder\Frame;

interface IStreamDecoder {

  /**
   * @return \Generator
   */
  public function receiver() : \Generator;
}
