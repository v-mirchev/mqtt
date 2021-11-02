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
   * @var \Mqtt\Protocol\Binary\Data\IBuffer
   */
  public $payload;

}
