<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

class Unsubscribe implements \Mqtt\Protocol\Encoder\Packet\IControlPacketEncoder {

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  /**
   * @var \Mqtt\Protocol\Binary\ITypedBuffer
   */
  protected $typedBuffer;

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @param \Mqtt\Protocol\Binary\ITypedBuffer $typedBuffer
   */
  public function __construct(
    \Mqtt\Protocol\Entity\Frame $frame,
    \Mqtt\Protocol\Binary\ITypedBuffer $typedBuffer
  ) {
    $this->frame = clone $frame;
    $this->typedBuffer = $typedBuffer;
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\Unsubscribe $packet
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    /* @var $packet \Mqtt\Protocol\Entity\Packet\Unsubscribe */

    if (! $packet->isA(\Mqtt\Protocol\IPacketType::UNSUBSCRIBE)) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $packet->getType() . '> is not UNSUBSCRIBE',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }

    $this->frame = clone $this->frame;
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::UNSUBSCRIBE;
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_UNSUBSCRIBE);

    $payload = clone $this->typedBuffer;
    $payload->decorate($this->frame->payload);

    $payload->appendUint16($packet->getId());

    foreach ($packet->topics as $topicFilter) {
      /* @var $subscription \Mqtt\Entity\Subscription */
      $payload->appendUtf8String($topicFilter);
    }

  }

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get(): \Mqtt\Protocol\Entity\Frame {
    return $this->frame;
  }

}
