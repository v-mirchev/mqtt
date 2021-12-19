<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class PubAck implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  use \Mqtt\Protocol\Decoder\Packet\ControlPacket\TValidators;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $identificator;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\PubAck
   */
  protected $pubAck;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $uint16
   * @param \Mqtt\Protocol\Entity\Packet\PubAck $pubAck
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint16 $uint16,
    \Mqtt\Protocol\Entity\Packet\PubAck $pubAck
  ) {
    $this->identificator = $uint16;
    $this->pubAck = $pubAck;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->identificator = clone $this->identificator;

    $this->assertPacketIs($frame, \Mqtt\Protocol\IPacketType::PUBACK);
    $this->assertPacketFlags($frame, \Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBACK);

    $this->identificator->decode($frame->payload);

    $this->assertPayloadConsumed($frame);

    $this->pubAck = clone $this->pubAck;
    $this->pubAck->setId($this->identificator->get());
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->pubAck;
  }

}
