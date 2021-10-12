<?php

namespace Mqtt\Protocol;

interface IHandler {

  /**
   * @return void
   */
  public function onProtocolConnect() : void;

  /**
   * @return void
   */
  public function onProtocolDisconnect() : void;

  /**
   * @return void
   */
  public function onTick() : void;

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet) : void;

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet) : void;

}
