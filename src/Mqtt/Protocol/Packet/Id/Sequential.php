<?php

namespace Mqtt\Protocol\Packet\Id;

class Sequential implements \Mqtt\Protocol\Packet\Id\IProvider {

  const MAX_VALUE_16BIT = 65535;

  /**
   * @var int
   */
  protected $id;

  /**
   * @var int[]
   */
  protected $freeIdentificators;

  public function __construct() {
    $this->id = 0;
    $this->freeIdentificators = [];
  }

  public function free(int $id) {
    $this->freeIdentificators[$id] = $id;
  }

  public function get(): int {
    $freeId = array_shift($this->freeIdentificators);
    if ($freeId) {
      unset($this->freeIdentificators[$freeId]);
      return $freeId;
    }

    $this->id ++;

    if ($this->id >= static::MAX_VALUE_16BIT) {
      throw new \Exception('Packet ID exceeds maximum value');
    }

    return $this->id;
  }

}
