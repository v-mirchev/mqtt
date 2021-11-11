<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class SubAck implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  use \Mqtt\Protocol\Decoder\Packet\ControlPacket\TValidators;

  /**
   * @var \Mqtt\Protocol\Binary\ITypedBuffer
   */
  protected $typedBuffer;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\SubAck
   */
  protected $subAck;

  /**
   * @param \Mqtt\Protocol\Binary\ITypedBuffer $typedBuffer
   * @param \Mqtt\Protocol\Entity\Packet\SubAck $subAck
   */
  public function __construct(
    \Mqtt\Protocol\Binary\ITypedBuffer $typedBuffer,
    \Mqtt\Protocol\Entity\Packet\SubAck $subAck
  ) {
    $this->typedBuffer = clone $typedBuffer;
    $this->subAck = clone $subAck;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->assertPacketIs($frame, \Mqtt\Protocol\IPacketType::SUBACK);
    $this->assertPacketFlags($frame, \Mqtt\Protocol\IPacketReservedBits::FLAGS_SUBACK);

    $typedBuffer = clone $this->typedBuffer;
    $typedBuffer->decorate($frame->payload);

    $this->subAck = clone $this->subAck;
    $this->subAck->setId($typedBuffer->getUint16()->get());

    $this->subAck->returnCodes = [];
    while (!$frame->payload->isEmpty()) {
      $returnCode = $typedBuffer->getUint8()->get();
      $this->validateSubscribeReturnCode($returnCode);
      $this->subAck->returnCodes[] = $returnCode;
    }
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->subAck;
  }

}
