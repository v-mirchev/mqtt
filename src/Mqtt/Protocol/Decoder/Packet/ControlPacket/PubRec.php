<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class PubRec implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  use \Mqtt\Protocol\Decoder\Packet\ControlPacket\TValidators;

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
    $this->identificator = $uint16;
    $this->pubRec = $pubRec;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->identificator = clone $this->identificator;

    $this->assertPacketIs($frame, \Mqtt\Protocol\IPacketType::PUBREC);
    $this->assertPacketFlags($frame, \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREC);

    $this->identificator->decode($frame->payload);

    $this->assertPayloadConsumed($frame);

    $this->pubRec = clone $this->pubRec;
    $this->pubRec->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubRec;
  }

}
