<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder;

interface IEncoder {

  /**
   * @param \Mqtt\Protocol\Entity\Packet\IPacket $packet
   * @return void
   */
  public function encode(\Mqtt\Protocol\Entity\Packet\IPacket $packet) : void;

  /**
   * @param callable $onPacketComplete
   * @return void
   */
  public function onEncodingCompleted(callable $onPacketComplete) : void;

}
