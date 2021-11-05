<?php

namespace Mqtt\Protocol\Decoder;

class Decoder implements \Mqtt\Protocol\Decoder\IDecoder {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Frame
   */
  protected $frameDecoder;

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\Decoder
   */
  protected $packetDecoder;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Receiver
   */
  protected $inputReceiver;

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\Frame $frameDecoder
   * @param \Mqtt\Protocol\Decoder\Packet\Decoder $packetDecoder
   */
  public function __construct(
    \Mqtt\Protocol\Decoder\Frame\Frame $frameDecoder,
    \Mqtt\Protocol\Decoder\Packet\Decoder $packetDecoder
  ) {
    $this->frameDecoder = $frameDecoder;
    $this->packetDecoder = $packetDecoder;

    $this->frameDecoder->onCompleted(\Closure::fromCallable([$this->packetDecoder, 'decode']));
    $this->packetDecoder->onCompleted(function (\Mqtt\Protocol\Entity\Packet\IPacket $packet) : void {});
    $this->inputReceiver = $this->frameDecoder->receiver();
  }

  public function input(string $chars = null): void {
    $this->inputReceiver->input($chars);
  }

  public function onCompleted(callable $onPacketComplete): void {
    $this->packetDecoder->onCompleted($onPacketComplete);
  }

}