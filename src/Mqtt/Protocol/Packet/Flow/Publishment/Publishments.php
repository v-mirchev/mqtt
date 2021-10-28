<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment;

class Publishments implements \Mqtt\Session\ISession {

  use \Mqtt\Session\TSession;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\Publishment
   */
  protected $publishmentIncomingFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\Publishment
   */
  protected $publishmentOutgoingFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\ISessionContext
   */
  protected $context;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\Publishment $publishmentIncomingFlow
   * @param \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\Publishment $publishmentOutgoingFlow
   * @param \Mqtt\Protocol\Packet\Flow\ISessionContext $context
   */
  public function __construct(
   \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\Publishment $publishmentIncomingFlow,
   \Mqtt\Protocol\Packet\Flow\Publishment\State\Outgoing\Publishment $publishmentOutgoingFlow,
    \Mqtt\Protocol\Packet\Flow\ISessionContext $context
  ) {
    $this->publishmentIncomingFlow = clone $publishmentIncomingFlow;
    $this->publishmentOutgoingFlow = clone $publishmentOutgoingFlow;
    $this->context = $context;
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::PUBLISH)) {
      $publishmentFlow = clone $this->publishmentIncomingFlow;
      if (isset($packet->id)) {
        $this->context->getPublishmentIncomingFlowQueue()->add($packet->id, $publishmentFlow);
      }
      $publishmentFlow->start();
      $publishmentFlow->onPacketReceived($packet);
    } elseif ($packet->is(\Mqtt\Protocol\Packet\IType::PUBREL)) {
      $publishmentFlow = $this->context->getPublishmentIncomingFlowQueue()->get($packet->id);
      $publishmentFlow->onPacketReceived($packet);
    } elseif (
        $packet->is(\Mqtt\Protocol\Packet\IType::PUBACK) ||
        $packet->is(\Mqtt\Protocol\Packet\IType::PUBREC) ||
        $packet->is(\Mqtt\Protocol\Packet\IType::PUBCOMP)) {
      $publishmentFlow = $this->context->getPublishmentOutgoingFlowQueue()->get($packet->id);
      $publishmentFlow->onPacketReceived($packet);
    }
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::PUBLISH)) {
      $publishmentFlow = clone $this->publishmentOutgoingFlow;
      $publishmentFlow->start();
      $publishmentFlow->onPacketSent($packet);
      if (isset($packet->id)) {
        $this->context->getPublishmentOutgoingFlowQueue()->add($packet->id, $publishmentFlow);
      }
    }
  }

  public function onTick(): void {
    foreach ($this->context->getPublishmentIncomingFlowQueue() as $publishment) {
      $publishment->onTick();
    }
    foreach ($this->context->getPublishmentOutgoingFlowQueue() as $publishment) {
      $publishment->onTick();
    }
  }

}
