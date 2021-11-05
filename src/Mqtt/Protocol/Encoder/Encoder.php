<?php

namespace Mqtt\Protocol\Encoder;

class Encoder implements \Mqtt\Protocol\Encoder\IEncoder {

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\IEncoder
   */
  protected $packetEncoder;

  /**
   * @var \Mqtt\Protocol\Encoder\Frame\OEncoder
   */
  protected $frameEncoder;

  /**
   * @param \Mqtt\Protocol\Encoder\Packet\IEncoder $packetEncoder
   * @param \Mqtt\Protocol\Encoder\Frame\IEncoder $frameEncoder
   */
  public function __construct(
    \Mqtt\Protocol\Encoder\Packet\IEncoder $packetEncoder,
    \Mqtt\Protocol\Encoder\Frame\IEncoder $frameEncoder
  ) {
    $this->packetEncoder = $packetEncoder;
    $this->frameEncoder = $frameEncoder;

    $this->packetEncoder->onCompleted(\Closure::fromCallable([$this->frameEncoder, 'encode']));
    $this->frameEncoder->onCompleted(function (string $data) : void {});
  }

  /**
   * @param \Mqtt\Protocol\Entity\Packet\IPacket $packet
   * @return void
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet) : void {
    $this->packetEncoder->encode($packet);
  }

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onEncodingCompleted(callable $onPacketComplete) : void {
    $this->frameEncoder->onCompleted($onPacketComplete);
  }

}
