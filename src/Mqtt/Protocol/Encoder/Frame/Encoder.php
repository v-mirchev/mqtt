<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Frame;

class Encoder implements \Mqtt\Protocol\Encoder\Frame\IEncoder {

  use \Mqtt\Protocol\Decoder\Frame\TReceiver;

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  protected $buffer;

  /**
   * @var \Mqtt\Protocol\Encoder\Frame\ControlHeader
   */
  protected $controlHeader;

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
   * @param \Mqtt\Protocol\Encoder\Frame\ControlHeader $controlHeader
   * @param \Mqtt\Protocol\Encoder\Frame\UintVariable $remainingLength
   */
  public function __construct(
    \Mqtt\Protocol\Binary\IBuffer $buffer,
    \Mqtt\Protocol\Encoder\Frame\ControlHeader $controlHeader,
    \Mqtt\Protocol\Encoder\Frame\UintVariable $remainingLength
  ) {
    $this->buffer = $buffer;
    $this->controlHeader = $controlHeader;
    $this->remainingLength = $remainingLength;

    $this->onFrameCompleted = function (string $data) {};
  }

  public function __clone() {
    $this->buffer = clone $this->buffer;
    $this->controlHeader = clone $this->controlHeader;
    $this->remainingLength = clone $this->remainingLength;
    $this->onFrameCompleted = function (string $data) {};
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function encode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->buffer->reset();

    $this->controlHeader->setPacketType($frame->packetType);
    $this->controlHeader->setFlags($frame->flags->get());
    $this->controlHeader->encode($this->buffer);

    $payload = (string) $frame->payload;
    $remainingLengtht  = strlen($payload);

    $this->remainingLength->set($remainingLengtht);
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
