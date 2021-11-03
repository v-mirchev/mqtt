<?php

namespace Mqtt\Protocol\Decoder\Frame;

class FixedHeader implements \Mqtt\Protocol\Decoder\Frame\IStreamDecoder {

  use \Mqtt\Protocol\Decoder\Frame\TReceiver;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\ControlHeader
   */
  protected $controlHeader;

  /**
   * @var UintVariable
   */
  protected $remainingLength;

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\ControlHeader $controlHeader
   * @param \Mqtt\Protocol\Decoder\Frame\UintVariable $remainingLength
   */
  public function __construct(
    \Mqtt\Protocol\Decoder\Frame\ControlHeader $controlHeader,
    \Mqtt\Protocol\Decoder\Frame\UintVariable $remainingLength
  ) {
    $this->controlHeader = clone $controlHeader;
    $this->remainingLength = clone $remainingLength;
  }

  public function __clone() {
    $this->controlHeader = clone $this->controlHeader;
    $this->remainingLength = clone $this->remainingLength;
  }

  /**
   * @return void
   */
  public function streamDecoder(): \Generator {

    $controlHeaderReceiver = $this->controlHeader->receiver();
    $remainingLengthReceiver = $this->remainingLength->receiver();

    while (true) {
      $char = yield;

      if (!$controlHeaderReceiver->isCompleted()) {
        $controlHeaderReceiver->input($char);
        continue;
      }

      if (!$remainingLengthReceiver->isCompleted()) {
        $remainingLengthReceiver->input($char);
      }

      if ($remainingLengthReceiver->isCompleted()) {
        break;
      }
    }
  }

  /**
   * @return int
   */
  public function getPacketType(): int {
    return $this->controlHeader->getPacketType();
  }

  /**
   * @return \Mqtt\Protocol\Binary\Data\Uint8
   */
  public function getFlags(): \Mqtt\Protocol\Binary\Data\Uint8 {
    return $this->controlHeader->getFlags();
  }

  /**
   * @return int
   */
  public function getRemainingLength(): int {
    return $this->remainingLength->get();
  }

}
