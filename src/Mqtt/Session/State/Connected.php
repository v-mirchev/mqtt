<?php declare(strict_types = 1);

namespace Mqtt\Session\State;

class Connected implements \Mqtt\Session\State\IState {

  use \Mqtt\Session\TSession;
  use \Mqtt\Session\State\TState;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Connection\Connection
   */
  protected $connectionFlow;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Connection\Connection $connectionFlow
  ) {
    $this->connectionFlow = $connectionFlow;
  }

  public function start() : void {
    throw new \Exception('Not allowed in this state');
  }

  public function stop() : void {
    $this->context->getProtocol()->disconnect();
  }

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function publish(\Mqtt\Entity\Message $message) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function subscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function unsubscribe(array $subscriptions) : void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolConnect(): void {
    throw new \Exception('Not allowed in this state');
  }

  public function onProtocolDisconnect() : void {
    $this->stateChanger->setState(\Mqtt\Session\State\IState::DISCONNECTED);
  }

  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    $this->connectionFlow->onPacketReceived($packet);
  }

  public function onStateEnter(): void {
    $this->connectionFlow->start();
  }

  public function onTick(): void {
    $this->connectionFlow->onTick();
  }

  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {

  }

}
