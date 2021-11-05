<?php

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class Publish implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Publish
   */
  protected $flags;

  /**
   * @var \Mqtt\Protocol\Binary\ITypedBuffer
   */
  protected $typedBuffer;

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @param \Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Publish $flags
   * @param \Mqtt\Protocol\Binary\ITypedBuffer $typedBuffer
   */
  public function __construct(
    \Mqtt\Protocol\Entity\Frame $frame,
    \Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Publish $flags,
    \Mqtt\Protocol\Binary\ITypedBuffer $typedBuffer
  ) {
    $this->frame = clone $frame;
    $this->flags = clone $flags;
    $this->typedBuffer = clone $typedBuffer;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\Publish $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    /* @var $packet \Mqtt\Protocol\Entity\Packet\Publish */

    if (! $packet->isA(\Mqtt\Protocol\IPacketType::PUBLISH)) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $packet->getType() . '> is not PUBLISH',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PUBLISH;

    $this->flags->dup = $packet->isDuplicate;
    $this->flags->retain = $packet->isRetain;
    $this->flags->qos = $packet->qosLevel;
    $this->flags->encode($this->frame->flags);

    $payload = clone $this->typedBuffer;
    $payload->decorate($this->frame->payload);

    $payload->appendUtf8String($this->topic);
    if ($packet->qos > \Mqtt\Entity\IQoS::AT_MOST_ONCE) {
      $payload->appendUint16($this->id);
    }
    $this->frame->payload->append($this->content);
  }

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get(): \Mqtt\Protocol\Entity\Frame {
    return $this->frame;
  }

}
