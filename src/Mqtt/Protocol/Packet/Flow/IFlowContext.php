<?php

namespace Mqtt\Protocol\Packet\Flow;

interface IFlowContext {

  /**
   * @return \Mqtt\Protocol\IPacketType
   */
  public function getOutgoingPacket() : \Mqtt\Protocol\IPacketType;

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function setOutgoingPacket(\Mqtt\Protocol\IPacketType $packet) : void;

  /**
   * @return \Mqtt\Protocol\IPacketType
   */
  public function getIncomingPacket() : \Mqtt\Protocol\IPacketType;

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function setIncomingPacket(\Mqtt\Protocol\IPacketType $packet) : void;

  /**
   * @return \Mqtt\Session\ISession
   */
  public function getFlow() : \Mqtt\Session\ISession;

}
