<?php

namespace Mqtt\Protocol\Entity;

class Frame {

  /**
   * @var int
   */
  public $packetType;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  public $flags;

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  public $payload;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $flags
   * @param \Mqtt\Protocol\Binary\IBuffer $payload
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Uint8 $flags,
    \Mqtt\Protocol\Binary\IBuffer $payload
  ) {
    $this->packetType = 0;
    $this->flags = $flags;
    $this->payload = $payload;
  }

  public function __clone() {
    $this->packetType = 0;
    $this->flags = clone $this->flags;
    $this->payload = clone $this->payload;
  }
}
