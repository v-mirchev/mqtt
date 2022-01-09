<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class Publish implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  use \Mqtt\Protocol\Encoder\Packet\ControlPacket\TValidators;

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
    $this->frame = $frame;
    $this->flags = $flags;
    $this->typedBuffer = $typedBuffer;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\Publish $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    /* @var $packet \Mqtt\Protocol\Entity\Packet\Publish */

    $this->assertPacketIs($packet, \Mqtt\Protocol\IPacketType::PUBLISH);
    $this->validateQosSetup($packet);

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PUBLISH;

    $this->flags = clone $this->flags;
    $this->flags->dup = $packet->isDuplicate;
    $this->flags->retain = $packet->isRetain;
    $this->flags->qos = $packet->qosLevel;
    $this->flags->encode($this->frame->flags);

    $payload = clone $this->typedBuffer;
    $payload->decorate($this->frame->payload);

    $payload->appendUtf8String($packet->topic);
    if ($packet->qosLevel > \Mqtt\Protocol\IQoS::AT_MOST_ONCE) {
      $payload->appendUint16($packet->getId());
    }
    $this->frame->payload->append($packet->message);
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\IPacket $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function validateQosSetup(\Mqtt\Protocol\Entity\Packet\IPacket $packet) : void {
    if (!in_array($packet->qosLevel, [
      \Mqtt\Protocol\IQoS::AT_MOST_ONCE,
      \Mqtt\Protocol\IQoS::AT_LEAST_ONCE,
      \Mqtt\Protocol\IQoS::EXACTLY_ONCE,
    ])) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Publish with unklnown Qos <' . $packet->qosLevel . '>',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_QOS
      );
    }

    if ($packet->qosLevel === \Mqtt\Protocol\IQoS::AT_MOST_ONCE) {
      if ($packet->getId() > 0) {
        throw new \Mqtt\Exception\ProtocolViolation(
          'Publish with Qos::AT_MOST_ONCE must have no ID set',
          \Mqtt\Exception\ProtocolViolation::INCORRECT_PUBLISH_ID_SETUP
        );
      }
    } elseif ($packet->getId() === 0) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Publish with Qos greater than AT_MOST_ONCE must have ID set',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PUBLISH_ID_SETUP
      );
    }
  }

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get(): \Mqtt\Protocol\Entity\Frame {
    return $this->frame;
  }

}
