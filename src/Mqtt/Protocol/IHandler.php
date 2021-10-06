<?php

namespace Mqtt\Protocol;

interface IHandler {

  /**
   * @return void
   */
  public function onConnect() : void;

  /**
   * @return void
   */
  public function onDisconnect() : void;

  /**
   * @return void
   */
  public function onTick() : void;

  /**
   * @param \Mqtt\Protocol\IPacket $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacket $packet) : void;

}
