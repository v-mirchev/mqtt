<?php

namespace Mqtt\Protocol\Decoder\Packet;

class Publish implements \Mqtt\Protocol\Decoder\IPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\Flags\Publish
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
    \Mqtt\Protocol\Decoder\Packet\Flags\Publish $flags,
    \Mqtt\Protocol\Binary\Data\Uint16 $id,
    \Mqtt\Protocol\Binary\Data\Utf8String $topic,
    \Mqtt\Protocol\Entity\Packet\Publish $publish
  ) {
    $this->flags = clone $flags;
    $this->id = clone $id;
    $this->topic = clone $topic;
    $this->publish = clone $publish;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame): void {
    if ($frame->packetType !== \Mqtt\Protocol\IPacketType::PUBLISH) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $frame->packetType . '> is not PUBLISH',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    $this->flags->decode($frame->flags);
    $this->topic->decode($frame->payload);
    if ($this->flags->qos > 0) {
      $this->id->decode($frame->payload);
    }
    $message = $frame->payload->getString();

    $this->publish = clone $this->publish;
    $this->publish->isDuplicate = $this->flags->dup;
    $this->publish->isRetain = $this->flags->retain;
    $this->publish->qosLevel = $this->flags->qos;
    if ($this->publish->qosLevel > 0) {
      $this->publish->setId($this->id->get());
    }
    $this->publish->topic = $this->topic->get();
    $this->publish->message = $message;
  }

  public function get(): \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->publish;
  }

}
