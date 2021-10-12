<?php

namespace Mqtt\Protocol;

interface IProtocol {

  /**
   * @param \Mqtt\Protocol\IHandler $session
   */
  public function setSession(\Mqtt\Protocol\IHandler $session);

  public function connect() : void;
  public function disconnect() : void;

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   */
  public function writePacket(\Mqtt\Protocol\Packet\IType $packet) : void;

  /**
   * @param int $type
   * @return \Mqtt\Protocol\Packet\IType
   */
  public function createPacket(int $type) : \Mqtt\Protocol\Packet\IType;

}
