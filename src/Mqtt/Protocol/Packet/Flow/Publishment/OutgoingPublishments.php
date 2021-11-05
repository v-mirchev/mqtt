<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment;

class OutgoingPublishments implements \Mqtt\Session\ISession {

  use \Mqtt\Session\TSession;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\Publishment
   */
  protected $publishmentOutgoingFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\ISessionContext
   */
  protected $context;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\Publishment $publishmentOutgoingFlow
   * @param \Mqtt\Protocol\Packet\Flow\ISessionContext $context
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\Publishment $publishmentOutgoingFlow,
    \Mqtt\Protocol\Packet\Flow\ISessionContext $context
  ) {
    $this->publishmentOutgoingFlow = clone $publishmentOutgoingFlow;
    $this->context = $context;
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    if (
      $packet->is(\Mqtt\Protocol\IPacketType::PUBACK) ||
      $packet->is(\Mqtt\Protocol\IPacketType::PUBREC) ||
      $packet->is(\Mqtt\Protocol\IPacketType::PUBCOMP)
    ) {
      $publishmentFlow = $this->context->getPublishmentOutgoingFlowQueue()->get($packet->id);
      $publishmentFlow->onPacketReceived($packet);
    }
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    $this->onNewPublishPacketSent($packet);
    $this->onDupPublishPacketSent($packet);
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onNewPublishPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacketType::PUBLISH) && !$packet->dup) {
      $publishmentFlow = clone $this->publishmentOutgoingFlow;
      $publishmentFlow->start();
      $publishmentFlow->onPacketSent($packet);
      if (isset($packet->id)) {
        $this->context->getPublishmentOutgoingFlowQueue()->add($packet->id, $publishmentFlow);
      }
    }
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onDupPublishPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacketType::PUBLISH) && $packet->dup) {
      $publishmentFlow = $this->context->getPublishmentOutgoingFlowQueue()->get($packet->id);
      $publishmentFlow->onPacketReceived($packet);
    }
  }

  public function onTick(): void {
    foreach ($this->context->getPublishmentOutgoingFlowQueue() as $publishment) {
      $publishment->onTick();
    }
  }

}
