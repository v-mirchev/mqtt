<?php

namespace Mqtt\Protocol\Decoder;

class Decoder implements \Mqtt\Protocol\Decoder\IPacketDecoder {

  /**
   * @var \Mqtt\Protocol\Decoder\DecoderFactory
   */
  protected $decoderFactory;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\IPacket
   */
  protected $packet;

  /**
   * @param \Mqtt\Protocol\Decoder\DecoderFactory $decoderFactory
   */
  public function __construct(\Mqtt\Protocol\Decoder\DecoderFactory $decoderFactory) {
    $this->decoderFactory = $decoderFactory;
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

  }

  /**
   * @return \Mqtt\Protocol\Entity\Packet\IPacket
   */
  public function get() : \Mqtt\Protocol\Entity\Packet\IPacket {
    return $this->packet;
  }

}
