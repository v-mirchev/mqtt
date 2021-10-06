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
   * @param \Mqtt\Protocol\IPacket $packet
   */
  public function writePacket(\Mqtt\Protocol\IPacket $packet) : void;

  /**
   * @param int $type
   * @return \Mqtt\Protocol\IPacket
   */
  public function createPacket(int $type) : \Mqtt\Protocol\IPacket;

}
