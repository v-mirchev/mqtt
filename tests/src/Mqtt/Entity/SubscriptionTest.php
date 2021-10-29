<?php

namespace Mqtt\Entity;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class SubscriptionTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;

  /**
   * @var TopicFilter
   */
  protected $topicFilter;

  /**
   * @var Subscription
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(Subscription::class);
  }

  public function testInitialState() {
    $this->assertEquals(false, $this->object->isSubscribed());
    $this->assertEquals(IQoS::AT_MOST_ONCE, $this->object->topicFilter->qos->qos);
    $this->assertNotNull($this->object->handler);
  }

  public function testCloneReinitsState() {
    $object = clone $this->object;
    $this->assertEquals(false, $object->isSubscribed());
    $this->assertEquals(IQoS::AT_MOST_ONCE, $object->topicFilter->qos->qos);
    $this->assertNotNull($object->handler);
  }

  public function testAtMostOnceFluentlyProxiesCall() {
    $this->assertEquals($this->object->atMostOnce(), $this->object);
    $this->assertEquals(IQoS::AT_MOST_ONCE, $this->object->topicFilter->qos->qos);
  }

  public function testAtLeastOnceFluentlyProxiesCall() {
    $this->assertEquals($this->object->atLeastOnce(), $this->object);
    $this->assertEquals(IQoS::AT_LEAST_ONCE, $this->object->topicFilter->qos->qos);
  }

  public function testExactlyOnceFluentlyProxiesCall() {
    $this->assertEquals($this->object->exactlyOnce(), $this->object);
    $this->assertEquals(IQoS::EXACTLY_ONCE, $this->object->topicFilter->qos->qos);
  }

  public function testSetFilterFluentlyProxiesCall() {
    $topicFilter = '#topic/1';
    $this->object->topicFilter($topicFilter);
    $this->assertEquals($topicFilter, $this->object->topicFilter->filter);
  }

  public function testSetHandlerFluentlySetsProperty() {
    $handler = new class implements \Mqtt\Client\Handler\ISubscription {
      public function onSubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribeFailed(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribed(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onSubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onUnsubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onUnsubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topicFilter): void {}
      public function onMessage(\Mqtt\Entity\Message $message): void {}
    };
    $this->assertEquals($this->object, $this->object->handler($handler));
    $this->assertEquals($handler, $this->object->handler);
  }

  public function testSetAsSubscribedFluentlySetsProperty() {
    $subscribed = true;
    $this->assertEquals($this->object, $this->object->setAsSubscribed($subscribed));
    $this->assertEquals($subscribed, $this->object->isSubscribed());
  }

}
