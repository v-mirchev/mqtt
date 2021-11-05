<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Subscription;

class Subscription implements
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
   * @var \Mqtt\Protocol\IPacketType
   */
  protected $outgoingPacket;

  /**
   * @var \Mqtt\Protocol\IPacketType
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
    $this->setState(\Mqtt\Protocol\Packet\Flow\IState::SUBSCRIBE_SUBSCRIBING);
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    $this->flowState->onPacketReceived($packet);
  }

  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
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
   * @return \Mqtt\Protocol\IPacketType
   */
  public function getOutgoingPacket() : \Mqtt\Protocol\IPacketType {
    return $this->outgoingPacket;
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function setOutgoingPacket(\Mqtt\Protocol\IPacketType $packet) : void {
    $this->outgoingPacket = $packet;
  }

  /**
   * @return \Mqtt\Protocol\IPacketType
   */
  public function getIncomingPacket() : \Mqtt\Protocol\IPacketType {
    return $this->incomingPacket;
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function setIncomingPacket(\Mqtt\Protocol\IPacketType $packet) : void {
    $this->incomingPacket = $packet;
  }

  public function getFlow(): \Mqtt\Session\ISession {
    return $this;
  }

}
