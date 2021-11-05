<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

interface IPacket {

  /**
   * @return int
   */
  public function getId() : int;

  /**
   * @param int $id
   * @return void
   */
  public function setId(int $id) : void;

  /**
   * @param int $packetType
   * @return bool
   */
  public function isA(int $packetType) : bool;

  /**
   * @return int
   */
  public function getType() : int;

}
