<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

trait TUnidentifiable  {

  public function getId(): int {
    throw new \Exception('Packet does not support ID');
  }

  public function setId(int $id): void {
    throw new \Exception('Packet does not support ID');
  }


}
