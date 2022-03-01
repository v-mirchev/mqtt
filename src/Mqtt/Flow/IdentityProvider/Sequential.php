<?php declare(strict_types = 1);

namespace Mqtt\Flow\IdentityProvider;

class Sequential implements \Mqtt\Flow\IdentityProvider\IProvider {

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

  public function __clone() {
    $this->id = 0;
    $this->freeIdentificators = [];
  }

  /**
   * @param int $id
   * @throws \Exception
   */
  public function free(int $id) {
    if (isset($this->freeIdentificators[$id])) {
      throw new \Exception('Packet ID <' . $id . '> is already as free');
    }

    if ($id > $this->id) {
      throw new \Exception('Packet ID <' . $id . '> could not be freed');
    }

    $this->freeIdentificators[$id] = $id;
  }

  /**
   * @return int
   * @throws \Exception
   */
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
