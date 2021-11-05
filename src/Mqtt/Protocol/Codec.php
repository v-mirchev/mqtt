<?php

namespace Mqtt\Protocol;

class Codec implements \Mqtt\Protocol\ICodec {

  /**
   * @var \Mqtt\Protocol\Encoder\IEncoder
   */
  protected $encoder;

  /**
   * @var \Mqtt\Protocol\Decoder\IDecoder
   */
  protected $decoder;

  /**
   * @param \Mqtt\Protocol\Encoder\IEncoder $encoder
   * @param \Mqtt\Protocol\Decoder\IDecoder $decoder
   */
  public function __construct(
    \Mqtt\Protocol\Encoder\IEncoder $encoder,
    \Mqtt\Protocol\Decoder\IDecoder $decoder
  ) {
    $this->encoder = clone $encoder;
    $this->decoder = clone $decoder;
  }

  /**
   * @param string $chars
   * @return void
   */
  public function decode(string $chars = null): void {
    $this->decoder->decode($chars);
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\IPacket $packet
   * @return void
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet) : void {
    $this->encoder->encode($packet);
  }

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onDecodingCompleted(callable $onPacketComplete): void {
    $this->decoder->onDecodingCompleted($onPacketComplete);
  }

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onEncodingCompleted(callable $onPacketComplete): void {
    $this->encoder->onEncodingCompleted($onPacketComplete);
  }

}
