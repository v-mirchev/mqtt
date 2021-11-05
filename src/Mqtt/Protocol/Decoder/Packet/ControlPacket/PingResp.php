<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class PingResp implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PingResp
   */
  protected $pingResp;

  /**
   * @param \Mqtt\Protocol\Entity\Packet\PingResp $pingResp
   */
  public function __construct(
    \Mqtt\Protocol\Entity\Packet\PingResp $pingResp
  ) {
    $this->pingResp = $pingResp;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PINGRESP) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $frame->packetType . '> is not PINGRESP',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PINGRESP) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet flags received do not match PINRESP reserved ones',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONTROL_HEADER_RESERVED_BITS
      );
    }

    if (!$frame->payload->isEmpty()) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Unknown payload data in CONNACK',
        \Mqtt\Exception\ProtocolViolation::UNKNOWN_PAYLOAD_DATA
      );
    }

    $this->pingResp = clone $this->pingResp;
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pingResp;
  }

}
