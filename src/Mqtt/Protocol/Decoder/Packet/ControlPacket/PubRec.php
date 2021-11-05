<?php

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class PubRec implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PubRec
   */
  protected $pubRec;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\PubRec $pubRec
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\PubRec $pubRec
  ) {
    $this->identificator = clone $uint16;
    $this->pubRec = clone $pubRec;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBREC) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $frame->packetType . '> is not PUBREC',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREC) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet flags received do not match PUBREC reserved ones',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONTROL_HEADER_RESERVED_BITS
      );
    }

    $this->identificator->decode($frame->payload);

    if (!$frame->payload->isEmpty()) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Unknown payload data in PUBREC',
        \Mqtt\Exception\ProtocolViolation::UNKNOWN_PAYLOAD_DATA
      );
    }

    $this->pubRec = clone $this->pubRec;
    $this->pubRec->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubRec;
  }

}
