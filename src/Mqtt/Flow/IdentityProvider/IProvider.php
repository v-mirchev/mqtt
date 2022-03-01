<?php declare(strict_types = 1);

namespace Mqtt\Flow\IdentityProvider;

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
