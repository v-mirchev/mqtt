<?php

namespace Mqtt\Session;

trait TSession  {

  public function start() : void {}

  public function stop() : void {}

  public function started() : void {}

  public function publish() : void {}

  public function subscribe() : void {}

  public function unsubscribe() : void {}

  public function onProtocolConnect(): void {}

  public function onProtocolDisconnect(): void {}

  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {}

  public function onTick(): void {}

  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {}

}
