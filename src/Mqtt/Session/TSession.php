<?php

namespace Mqtt\Session;

trait TSession  {

  /**
   * @var \Mqtt\IClient
   */
  protected $client;

  public function setClient(\Mqtt\Client\IClient $client): void {
    $this->client = $client;
  }

  public function start() : void {}

  public function stop() : void {}

  /**
   * @param \Mqtt\Entity\Message $message
   * @return void
   */
  public function publish(\Mqtt\Entity\Message $message) : void {}

  /**
   * @param \Mqtt\Entity\Subscription[] $subscriptions
   * @return void
   */
  public function subscribe(array $subscriptions) : void {}

  /**
   * @param \Mqtt\Entity\Subscription[] $subscriptions
   * @return void
   */
  public function unsubscribe(array $subscriptions) : void {}

  public function onProtocolConnect(): void {}

  public function onProtocolDisconnect(): void {}

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {}

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {}

  public function onTick(): void {}

}
