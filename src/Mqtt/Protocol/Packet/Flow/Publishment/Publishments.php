<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment;

class Publishments implements \Mqtt\Session\ISession {

  use \Mqtt\Session\TSession;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Publishment\IncomingPublishments
   */
  protected $incomingPublishments;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Publishment\OutgoingPublishments
   */
  protected $outgoingPublishments;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Publishment\IncomingPublishments $incomingPublishments
   * @param \Mqtt\Protocol\Packet\Flow\Publishment\OutgoingPublishments $outgoingPublishments
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Publishment\IncomingPublishments $incomingPublishments,
    \Mqtt\Protocol\Packet\Flow\Publishment\OutgoingPublishments $outgoingPublishments
  ) {
    $this->incomingPublishments = $incomingPublishments;
    $this->outgoingPublishments = $outgoingPublishments;
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    $this->incomingPublishments->onPacketReceived($packet);
    $this->outgoingPublishments->onPacketReceived($packet);
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    $this->incomingPublishments->onPacketSent($packet);
    $this->outgoingPublishments->onPacketSent($packet);
  }

  public function onTick(): void {
    $this->incomingPublishments->onTick();
    $this->outgoingPublishments->onTick();
  }

}
