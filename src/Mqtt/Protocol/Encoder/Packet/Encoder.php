<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet;

class Encoder implements \Mqtt\Protocol\Encoder\Packet\IEncoder {

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\Factory
   */
  protected $encoderFactory;

  /**
   * @var callable
   */
  protected $onFrameCompleted;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  /**
   * @param \Mqtt\Protocol\Encoder\Packet\Factory $encoderFactory
   */
  public function __construct(\Mqtt\Protocol\Encoder\Packet\Factory $encoderFactory) {
    $this->encoderFactory = $encoderFactory;

    $this->onFrameCompleted = function (\Mqtt\Protocol\Entity\Frame $frame) : void {};
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\IPacket $packet
   * @return void
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet): void {
    $encoder = $this->encoderFactory->create($packet->getType());
    $encoder->encode($packet);

    $this->frame = $encoder->get();
    if (!$packet->isA($this->frame->packetType)) {
      throw new \Mqtt\Exception\ProtocolViolation('Unexpected frame encoded');
    }

    $onCompletedCallback = $this->onFrameCompleted;
    $onCompletedCallback($this->frame);
  }

  /**
   * @param callable $onPacketCompleted
   */
  public function onCompleted(callable $onPacketCompleted) :void {
    $this->onFrameCompleted = $onPacketCompleted;
  }

  /**
   * @return \Mqtt\Protocol\Entity\Frame
   */
  public function get() : \Mqtt\Protocol\Entity\Frame {
    return $this->frame;
  }

}
