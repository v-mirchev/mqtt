<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing;

class Publishment implements
  \Mqtt\Session\ISession,
  \Mqtt\Protocol\Packet\Flow\IStateChanger,
  \Mqtt\Protocol\Packet\Flow\IFlowContext
{

  use \Mqtt\Session\TSession;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\IState
   */
  protected $flowState;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Factory
   */
  protected $stateFactory;

  /**
   * @var \Mqtt\Protocol\Packet\IType
   */
  protected $outgoingPacket;

  /**
   * @var \Mqtt\Protocol\Packet\IType
   */
  protected $incomingPacket;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Factory $stateFactory
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Factory $stateFactory
  ) {
    $this->stateFactory = $stateFactory;
  }

  public function start() : void {
    $this->setState(\Mqtt\Protocol\Packet\Flow\IState::PUBLISH_OUTGOING_PUBLISHING);
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->flowState->onPacketReceived($packet);
  }

  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->flowState->onPacketSent($packet);
  }

  public function onTick(): void {
    $this->flowState->onTick();
  }

  /**
   * @param string $sessionState
   * @return void
   */
  public function setState(string $sessionState) : void {
    error_log('FLOW::' . $sessionState);
    $previous = $this->flowState;
    $this->flowState = $this->stateFactory->create($sessionState);
    $this->flowState->setStateChanger($this);
    $this->flowState->setSubcontext($this);
    $this->flowState->onStateEnter();
    unset($previous);
  }

  /**
   * @return \Mqtt\Protocol\Packet\IType
   */
  public function getOutgoingPacket() : \Mqtt\Protocol\Packet\IType {
    return $this->outgoingPacket;
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function setOutgoingPacket(\Mqtt\Protocol\Packet\IType $packet) : void {
    $this->outgoingPacket = $packet;
  }

  /**
   * @return \Mqtt\Protocol\Packet\IType
   */
  public function getIncomingPacket() : \Mqtt\Protocol\Packet\IType {
    return $this->incomingPacket;
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function setIncomingPacket(\Mqtt\Protocol\Packet\IType $packet) : void {
    $this->incomingPacket = $packet;
  }

  public function getFlow(): \Mqtt\Session\ISession {
    return $this;
  }

}
