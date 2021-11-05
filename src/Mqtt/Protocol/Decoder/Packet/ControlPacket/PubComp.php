<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class PubComp implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PubComp
   */
  protected $pubComp;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\PubComp $pubComp
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\PubComp $pubComp
  ) {
    $this->identificator = clone $uint16;
    $this->pubComp = clone $pubComp;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBCOMP) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $frame->packetType . '> is not PUBCOMP',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBCOMP) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet flags received do not match PUBCOMP reserved ones',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONTROL_HEADER_RESERVED_BITS
      );
    }


    $this->identificator->decode($frame->payload);

    if (!$frame->payload->isEmpty()) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Unknown payload data in PUBCOMP',
        \Mqtt\Exception\ProtocolViolation::UNKNOWN_PAYLOAD_DATA
      );
    }

    $this->pubComp = clone $this->pubComp;
    $this->pubComp->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubComp;
  }

}
