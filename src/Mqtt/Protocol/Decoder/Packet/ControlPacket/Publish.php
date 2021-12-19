<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

class Publish implements \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags\Publish
   */
  protected $flags;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $id;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Utf8String
   */
  protected $topic;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\Publish
   */
  protected $publish;

  public function __construct(
    \Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags\Publish $flags,
    \Mqtt\Protocol\Binary\Data\Uint16 $id,
    \Mqtt\Protocol\Binary\Data\Utf8String $topic,
    \Mqtt\Protocol\Entity\Packet\Publish $publish
  ) {
    $this->flags = $flags;
    $this->id = $id;
    $this->topic = $topic;
    $this->publish = $publish;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    $this->id = clone $this->id;
    $this->topic = clone $this->topic;
    $this->publish = clone $this->publish;

    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBLISH) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $frame->packetType . '> is not PUBLISH',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    $this->flags->decode($frame->flags);
    $this->topic->decode($frame->payload);
    if ($this->flags->getQos() !== \Mqtt\Protocol\Entity\IQoS::AT_MOST_ONCE) {
      $this->id->decode($frame->payload);
    }
    $message = $frame->payload->getString();

    $this->publish->isDuplicate = $this->flags->isDuplicate();
    $this->publish->isRetain = $this->flags->isRetain();
    $this->publish->qosLevel = $this->flags->getQos();
    if ($this->publish->qosLevel !== \Mqtt\Protocol\Entity\IQoS::AT_MOST_ONCE) {
      $this->publish->setId($this->id->get());
    }
    $this->publish->topic = $this->topic->get();
    $this->publish->message = $message;
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->publish;
  }

}
