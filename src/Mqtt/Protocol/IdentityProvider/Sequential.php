<?php declare(strict_types = 1);

namespace Mqtt\Protocol\IdentityProvider;

class Sequential implements \Mqtt\Protocol\IdentityProvider\IProvider {

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
    $this->freeIdentificators[] = $id;
  }

  public function get(): int {
    $freeId = array_shift($this->freeIdentificators);
    if ($freeId) {
      return $freeId;
    }

    $this->id ++;

    if ($this->id >= static::MAX_VALUE_16BIT) {
      throw new \Exception('Packet ID exceeds maximum value');
    }

    return $this->id;
  }

}
