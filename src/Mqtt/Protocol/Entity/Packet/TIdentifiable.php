<?php

namespace Mqtt\Protocol\Entity\Packet;

trait TIdentifiable  {

  /**
   * @var int
   */
  protected $id;

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @param int $id
   * @return void
   */
  public function setId(int $id): void {
    $this->id = $id;
  }

}
