<?php

namespace Mqtt\Protocol\Packet\Flow;

interface IFlowContext {

  /**
   * @return \Mqtt\Protocol\Packet\IType
   */
  public function getOutgoingPacket() : \Mqtt\Protocol\Packet\IType;

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function setOutgoingPacket(\Mqtt\Protocol\Packet\IType $packet) : void;

  /**
   * @return \Mqtt\Protocol\Packet\IType
   */
  public function getIncomingPacket() : \Mqtt\Protocol\Packet\IType;

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function setIncomingPacket(\Mqtt\Protocol\Packet\IType $packet) : void;

  /**
   * @return \Mqtt\Session\ISession
   */
  public function getFlow() : \Mqtt\Session\ISession;

}
