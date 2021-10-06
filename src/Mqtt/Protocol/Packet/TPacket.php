<?php

namespace Mqtt\Protocol\Packet;

trait TPacket {

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {}

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {}

}
