<?php

namespace Mqtt\PacketIdProvider;

interface IPacketIdProvider {

  /**
   * @return int
   */
  public function get() : int;

  /**
   * @param int $id
   */
  public function free(int $id);

}
