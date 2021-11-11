<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class PingResp implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  use \Mqtt\Protocol\Decoder\Packet\ControlPacket\TValidators;

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
    $this->assertPacketIs($frame, \Mqtt\Protocol\IPacketType::PINGRESP);
    $this->assertPacketFlags($frame, \Mqtt\Protocol\IPacketReservedBits::FLAGS_PINGRESP);
    $this->assertPayloadConsumed($frame);

    $this->pingResp = clone $this->pingResp;
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pingResp;
  }

}
