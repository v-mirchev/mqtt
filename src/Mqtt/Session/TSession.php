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

  public function publish() : void {}

  public function subscribe(array $subscriptions) : void {}

  public function unsubscribe(array $subscriptions) : void {}

  public function onProtocolConnect(): void {}

  public function onProtocolDisconnect(): void {}

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {}

  public function onTick(): void {}

  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {}

}
