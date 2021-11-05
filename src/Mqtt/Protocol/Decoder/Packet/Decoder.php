<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet;

class Decoder implements \Mqtt\Protocol\Decoder\Packet\IDecoder {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\Factory
   */
  protected $decoderFactory;

  /**
   * @var callable
   */
  protected $onPacketCompleted;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\IPacket
   */
  protected $packet;

  /**
   * @param \Mqtt\Protocol\Decoder\Packet\Factory $decoderFactory
   */
  public function __construct(\Mqtt\Protocol\Decoder\Packet\Factory $decoderFactory) {
    $this->decoderFactory = $decoderFactory;

    $this->onPacketCompleted = function (\Mqtt\Protocol\Entity\Packet\IPacket $packet) : void {};
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Entity\Frame $frame) : void {
    $decoder = $this->decoderFactory->create($frame->packetType);

    $decoder->decode($frame);
    if (!$frame->payload->isEmpty()) {
      throw new \Mqtt\Exception\ProtocolViolation('Unprocessed data found in frame payload');
    }

    $this->packet = $decoder->get();
    if (!$this->packet->isA($frame->packetType)) {
      throw new \Mqtt\Exception\ProtocolViolation('Unexpected packet decoded');
    }

    $onCompletedCallback = $this->onPacketCompleted;
    $onCompletedCallback($this->packet);
  }

  /**
   * @param callable $onPacketCompleted
   * @return void
   */
  public function onCompleted(callable $onPacketCompleted) : void {
    $this->onPacketCompleted = $onPacketCompleted;
  }

  /**
   * @return \Mqtt\Protocol\Entity\Packet\IPacket
   */
  public function get() : \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->packet;
  }

}
