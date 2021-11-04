<?php

namespace Mqtt\Protocol\Entity\Packet;

trait TUnidentifiable  {

  public function getId(): int {
    throw new Exception('ConnAck does not support ID');
  }

  public function setId(int $id): void {
    throw new Exception('ConnAck does not support ID');
  }


}
