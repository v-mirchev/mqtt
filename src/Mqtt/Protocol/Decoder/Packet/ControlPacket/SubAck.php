<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class SubAck implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

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
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::SUBACK) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $frame->packetType . '> is not SUBACK',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_SUBACK) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet flags received do not match SUBACK reserved ones',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONTROL_HEADER_RESERVED_BITS
      );
    }

    $typedBuffer = clone $this->typedBuffer;
    $typedBuffer->decorate($frame->payload);

    $this->subAck = clone $this->subAck;
    $this->subAck->setId($typedBuffer->getUint16()->get());

    $this->subAck->returnCodes = [];
    while (!$frame->payload->isEmpty()) {
      $returnCode = $typedBuffer->getUint8()->get();
      if (!in_array($returnCode, [ 0x00, 0x01, 0x02, 0x80] )) {
        throw new \Mqtt\Exception\ProtocolViolation(
          'Return codes received do not match SUBACK allowed ones',
          \Mqtt\Exception\ProtocolViolation::INCORRECT_SUBACK_RETURN_CODE
        );
      }
      $this->subAck->returnCodes[] = $returnCode;
    }
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->subAck;
  }

}
