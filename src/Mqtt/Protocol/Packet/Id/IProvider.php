<?php

namespace Mqtt\Protocol\Packet\Id;

interface IProvider {

  /**
   * @return int
   */
  public function get() : int;

  /**
   * @param int $id
   */
  public function free(int $id);

}
