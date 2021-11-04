<?php

namespace Mqtt\Protocol\Decoder\Packet;

class PingResp implements \Mqtt\Protocol\Decoder\IPacketDecoder {

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
      throw new \Exception('Packet type received <' . $frame->packetType . '> is not PINGRESP');
    }

    if ($frame->flags->get() !== \Mqtt\Protocol\IPacketReservedBits::FLAGS_PINGRESP) {
      throw new \Mqtt\Exception\ProtocolViolation('Packet flags received do not match PINGRESP reserved ones');
    }

    $this->pingResp = clone $this->pingResp;
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pingResp;
  }

}
