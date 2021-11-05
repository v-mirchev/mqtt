<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame;

interface IStreamDecoder {

  /**
   * @return \Generator
   */
  public function streamDecoder() : \Generator;

  /**
   * @return \Mqtt\Protocol\Decoder\Frame\Receiver
   */
  public function receiver() : \Mqtt\Protocol\Decoder\Frame\Receiver;

}
