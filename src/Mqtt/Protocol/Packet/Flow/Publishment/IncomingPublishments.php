<?php

namespace Mqtt\Protocol\Packet\Flow\Publishment;

class IncomingPublishments implements \Mqtt\Session\ISession {

  use \Mqtt\Session\TSession;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\Publishment
   */
  protected $publishmentIncomingFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\ISessionContext
   */
  protected $context;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\Publishment $publishmentIncomingFlow
   * @param \Mqtt\Protocol\Packet\Flow\ISessionContext $context
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Publishment\State\Incoming\Publishment $publishmentIncomingFlow,
    \Mqtt\Protocol\Packet\Flow\ISessionContext $context
  ) {
    $this->publishmentIncomingFlow = clone $publishmentIncomingFlow;
    $this->context = $context;
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    $this->onPublishPacketReceived($packet);
    $this->onPublishReleasePacketReceived($packet);
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPublishPacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::PUBLISH)) {
      $publishmentFlow = clone $this->publishmentIncomingFlow;
      if (isset($packet->id)) {
        $this->context->getPublishmentIncomingFlowQueue()->add($packet->id, $publishmentFlow);
      }
      $publishmentFlow->start();
      $publishmentFlow->onPacketReceived($packet);
    }
  }

  /**
   * @param \Mqtt\Protocol\Packet\IType $packet
   * @return void
   */
  public function onPublishReleasePacketReceived(\Mqtt\Protocol\Packet\IType $packet): void {
    if ($packet->is(\Mqtt\Protocol\Packet\IType::PUBREL)) {
      $publishmentFlow = $this->context->getPublishmentIncomingFlowQueue()->get($packet->id);
      $publishmentFlow->onPacketReceived($packet);
    }
  }

  public function onTick(): void {
    foreach ($this->context->getPublishmentIncomingFlowQueue() as $publishment) {
      $publishment->onTick();
    }
  }

}
