<?php

namespace Mqtt\Protocol\Decoder\Frame;

class Frame implements \Mqtt\Protocol\Decoder\Frame\IStreamDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\IBuffer
   */
  protected $buffer;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\FixedHeader
   */
  protected $fixedHeader;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Payload
   */
  protected $payload;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $entityPrototype;

  /**
   * @var \Generator
   */
  protected $fixedHeaderReceiver;

  /**
   * @var \Generator
   */
  protected $payloadReceiver;

  /**
   * @var callable
   */
  protected $onFrameCompleted;

  /**
   * @param \Mqtt\Protocol\Binary\Data\IBuffer $buffer
   * @param \Mqtt\Protocol\Decoder\Frame\FixedHeader $fixedHeader
   * @param \Mqtt\Protocol\Decoder\Frame\Payload $payload
   * @param \Mqtt\Protocol\Entity\Frame $entityPrototype
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\IBuffer $buffer,
    \Mqtt\Protocol\Decoder\Frame\FixedHeader $fixedHeader,
    \Mqtt\Protocol\Decoder\Frame\Payload $payload,
    \Mqtt\Protocol\Entity\Frame $entityPrototype
  ) {
    $this->buffer = clone $buffer;
    $this->fixedHeader = clone $fixedHeader;
    $this->payload = clone $payload;
    $this->entityPrototype = clone $entityPrototype;
  }

  public function __clone() {
    $this->buffer = clone $this->buffer;
    $this->fixedHeader = clone $this->fixedHeader;
    $this->payload = clone $this->payload;
    $this->entityPrototype = clone $this->entityPrototype;
  }

  /**
   * @param string $chars
   */
  public function receiver() : \Generator {
    $this->fixedHeaderReceiver = $this->fixedHeader->receiver();
    $this->payloadReceiver = $this->payload->receiver();

    while (true) {
      $chars = yield;
      $this->buffer->append((string)$chars);
      $this->consumeBuffer();
    }
  }

  protected function consumeBuffer() : void {
    foreach ($this->buffer as $char) {

      if ($this->fixedHeaderReceiver->valid()) {
        $this->fixedHeaderReceiver->send($char);
        if (!$this->fixedHeaderReceiver->valid() && $this->fixedHeader->getRemainingLength() === 0) {
          $this->complete();
          return;
        }
        continue;
      }

      $this->payload->setLength($this->fixedHeader->getRemainingLength());
      if ($this->payloadReceiver->valid()) {
        $this->payloadReceiver->send($char);
      }

      if (!$this->payloadReceiver->valid()) {
        $this->complete();
        return;
      }

    }
  }

  protected function complete() : void {
    $this->fixedHeaderReceiver = $this->fixedHeader->receiver();
    $this->payloadReceiver = $this->payload->receiver();

    $entity = clone $this->entityPrototype;
    $entity->packetType = $this->fixedHeader->getPacketType();
    $entity->flags = $this->fixedHeader->getFlags();
    $entity->payload = $this->payload->get();

    $onFrameCompleted = $this->onFrameCompleted;
    $onFrameCompleted($entity);
  }


  /**
   * @param callable $onFrameCompleted
   */
  public function onCompleted(callable $onFrameCompleted) : void {
    $this->onFrameCompleted = $onFrameCompleted;
  }

}
