<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow\Unsubscription;

class Unsubscriptions implements \Mqtt\Session\ISession {

  use \Mqtt\Session\TSession;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\Unsubscription\Unsubscription
   */
  protected $unsubscriptionFlow;

  /**
   * @var \Mqtt\Protocol\Packet\Flow\ISessionContext
   */
  protected $context;

  /**
   * @param \Mqtt\Protocol\Packet\Flow\Unsubscription\Unsubscription $unsubscriptionFlow
   * @param \Mqtt\Protocol\Packet\Flow\ISessionContext $context
   */
  public function __construct(
    \Mqtt\Protocol\Packet\Flow\Unsubscription\Unsubscription $unsubscriptionFlow,
    \Mqtt\Protocol\Packet\Flow\ISessionContext $context
  ) {
    $this->unsubscriptionFlow = $unsubscriptionFlow;
    $this->context = $context;
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketReceived(\Mqtt\Protocol\IPacketType $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacketType::UNSUBACK)) {
      $this->context->getUnsubscriptionsFlowQueue()->get($packet->id)->onPacketReceived($packet);
    }
  }

  /**
   * @param \Mqtt\Protocol\IPacketType $packet
   * @return void
   */
  public function onPacketSent(\Mqtt\Protocol\IPacketType $packet): void {
    if ($packet->is(\Mqtt\Protocol\IPacketType::UNSUBSCRIBE)) {
      $unsubscriptionFlow = clone $this->unsubscriptionFlow;
      $unsubscriptionFlow->start();
      $unsubscriptionFlow->onPacketSent($packet);
      $this->context->getUnsubscriptionsFlowQueue()->add($packet->id, $unsubscriptionFlow);
    }
  }

  public function onTick(): void {
    foreach ($this->context->getUnsubscriptionsFlowQueue() as $unsubscription) {
      $unsubscription->onTick();
    }
  }

}
