<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class PubRel implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PubRel
   */
  protected $pubRel;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\PubRel $pubRel
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\PubRel $pubRel
  ) {
    $this->identificator = clone $uint16;
    $this->pubRel = clone $pubRel;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBREL) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $frame->packetType . '> is not PUBREL',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREL) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet flags received do not match PUBREL reserved ones',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONTROL_HEADER_RESERVED_BITS
      );
    }

    $this->identificator->decode($frame->payload);

    if (!$frame->payload->isEmpty()) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Unknown payload data in PUBREL',
        \Mqtt\Exception\ProtocolViolation::UNKNOWN_PAYLOAD_DATA
      );
    }

    $this->pubRel = clone $this->pubRel;
    $this->pubRel->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubRel;
  }

}
