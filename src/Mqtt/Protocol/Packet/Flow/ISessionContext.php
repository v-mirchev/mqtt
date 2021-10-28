<?php

namespace Mqtt\Protocol\Packet\Flow;

interface ISessionContext {

  /**
   * @return \Mqtt\Entity\Configuration\Session
   */
  public function getSessionConfiguration() : \Mqtt\Entity\Configuration\Session;

  /**
   * @return \Mqtt\Session\IStateChanger
   */
  public function getSessionStateChanger() : \Mqtt\Session\IStateChanger;

  /**
   * @return \Mqtt\Protocol\Packet\Id\IProvider
   */
  public function getIdProvider() : \Mqtt\Protocol\Packet\Id\IProvider;

  /**
   * @return \Mqtt\Protocol\IProtocol
   */
  public function getProtocol() : \Mqtt\Protocol\IProtocol;

  /**
   * @return \Mqtt\Protocol\Packet\Flow\IQueue
   */
  public function getSubscriptionsFlowQueue(): \Mqtt\Protocol\Packet\Flow\IQueue;

  /**
   * @return \Mqtt\Protocol\Packet\Flow\IQueue
   */
  public function getUnsubscriptionsFlowQueue(): \Mqtt\Protocol\Packet\Flow\IQueue;

  /**
   * @return \Mqtt\Protocol\Packet\Flow\IQueue
   */
  public function getPublishmentIncomingFlowQueue(): \Mqtt\Protocol\Packet\Flow\IQueue;

  /**
   * @return \Mqtt\Protocol\Packet\Flow\IQueue
   */
  public function getPublishmentOutgoingFlowQueue(): \Mqtt\Protocol\Packet\Flow\IQueue;

  /**
   * @return \Mqtt\Client\Subscriptions
   */
  public function getSubscriptions(): \Mqtt\Client\Subscriptions;

}
