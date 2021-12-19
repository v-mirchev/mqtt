<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class UnsubAck implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  use \Mqtt\Protocol\Decoder\Packet\ControlPacket\TValidators;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\UnsubAck
   */
  protected $unsubAck;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\UnsubAck $unsubAck
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\UnsubAck $unsubAck
  ) {
    $this->identificator = $uint16;
    $this->unsubAck = $unsubAck;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->identificator = clone $this->identificator;
    $this->unsubAck = clone $this->unsubAck;

    $this->assertPacketIs($frame, \Mqtt\Protocol\IPacketType::UNSUBACK);
    $this->assertPacketFlags($frame, \Mqtt\Protocol\IPacketReservedBits::FLAGS_UNSUBACK);

    $this->identificator->decode($frame->payload);

    $this->assertPayloadConsumed($frame);

    $this->unsubAck->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->unsubAck;
  }

}
