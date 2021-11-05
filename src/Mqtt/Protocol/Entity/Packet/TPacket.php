<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

trait TPacket  {

  /**
   * @param int $packetType
   * @return bool
   */
  public function isA(int $packetType): bool {
    return static::TYPE === $packetType;
  }

  /**
   * @return int
   */
  public function getType(): int {
    return static::TYPE;
  }

}
