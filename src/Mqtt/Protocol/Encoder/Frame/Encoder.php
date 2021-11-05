<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Frame;

class Encoder implements \Mqtt\Protocol\Encoder\Frame\IEncoder {

  use \Mqtt\Protocol\Decoder\Frame\TReceiver;

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  protected $buffer;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $packetType;

  /**
   * @var \Mqtt\Protocol\Encoder\Frame\UintVariable
   */
  protected $remainingLength;

  /**
   * @var callable
   */
  protected $onCompleted;

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $packetType
   * @param \Mqtt\Protocol\Encoder\Frame\UintVariable $remainingLength
   */
  public function __construct(
    \Mqtt\Protocol\Binary\IBuffer $buffer,
    \Mqtt\Protocol\Binary\Data\Uint8 $packetType,
    \Mqtt\Protocol\Encoder\Frame\UintVariable $remainingLength
  ) {
    $this->buffer = $buffer;
    $this->packetType = $packetType;
    $this->remainingLength = $remainingLength;

    $this->onFrameCompleted = function (string $data) {};
  }

  public function __clone() {
    $this->buffer = clone $this->buffer;
    $this->remainingLength = clone $this->remainingLength;
    $this->onFrameCompleted = function (string $data) {};
  }

  public function encode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->buffer->reset();

    $this->packetType->set($frame->packetType);
    $this->packetType->encode($this->buffer);

    $frame->flags->encode($this->buffer);

    $payload = (string) $frame->payload;
    $this->remainingLength->set(strlen($payload));
    $this->remainingLength->encode($this->buffer);
    $this->buffer->append($payload);

    $onCompletedCallback = $this->onCompleted;
    $onCompletedCallback((string) $this->buffer);
  }

  /**
   * @param callable $onFrameCompleted
   */
  public function onCompleted(callable $onFrameCompleted) : void {
    $this->onCompleted = $onFrameCompleted;
  }

}
