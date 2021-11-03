<?php

namespace Mqtt\Protocol\Decoder\Frame;

class Receiver {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\IStreamDecoder
   */
  protected $streamDecoder;

  /**
   * @var \Generator
   */
  protected $receiver;

  public function __invoke(\Mqtt\Protocol\Decoder\Frame\IStreamDecoder $streamDecoder) : void {
    $this->streamDecoder = $streamDecoder;
  }

  /**
   * @param string $chars
   */
  public function input(string $chars = null) : void {
    if (!is_null($chars)) {
      $this->getReceiver()->send($chars);
    }
  }

  /**
   * @return bool
   */
  public function isCompleted() : bool {
    return !$this->getReceiver()->valid();
  }

  /**
   * @return \Generator
   */
  protected function getReceiver() : \Generator {
    if (!$this->receiver) {
      $this->receiver = $this->streamDecoder->streamDecoder();
    }
    return $this->receiver;
  }

  public function rewind() : void {
    $this->receiver = $this->streamDecoder->streamDecoder();
  }

}
