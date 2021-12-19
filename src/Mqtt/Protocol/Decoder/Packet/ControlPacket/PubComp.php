<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class PubComp implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  use \Mqtt\Protocol\Decoder\Packet\ControlPacket\TValidators;

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
    $this->identificator = $uint16;
    $this->pubComp = $pubComp;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->identificator = clone $this->identificator;

    $this->assertPacketIs($frame, \Mqtt\Protocol\IPacketType::PUBCOMP);
    $this->assertPacketFlags($frame, \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBCOMP);

    $this->identificator->decode($frame->payload);

    $this->assertPayloadConsumed($frame);

    $this->pubComp = clone $this->pubComp;
    $this->pubComp->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubComp;
  }

}
