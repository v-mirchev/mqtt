<?php declare(strict_types = 1);

namespace Mqtt\Entity;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class MessageTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Entity\Message
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(Message::class);
  }

  public function testInitialState() {
    $this->assertEquals(false, $this->object->isDup);
    $this->assertEquals(false, $this->object->isRetain);
    $this->assertEmpty($this->object->content);
    $this->assertEmpty($this->object->topic);
    $this->assertEquals(IQoS::AT_MOST_ONCE, $this->object->qos->qos);
    $this->assertNotNull($this->object->handler);
  }

  public function testCloneReinitsState() {
    $object = clone $this->object;
    $this->assertEquals(false, $object->isDup);
    $this->assertEquals(false, $object->isRetain);
    $this->assertEmpty($object->content);
    $this->assertEmpty($object->topic);
    $this->assertEquals(IQoS::AT_MOST_ONCE, $object->qos->qos);
    $this->assertNotNull($object->handler);
  }

  public function testQoSFluentlyProxiesCall() {
    $this->assertEquals($this->object, $this->object->atMostOnce());
    $this->assertEquals($this->object->qos->qos, QoS::AT_MOST_ONCE);

    $this->assertEquals($this->object, $this->object->atLeastOnce());
    $this->assertEquals($this->object->qos->qos, QoS::AT_LEAST_ONCE);

    $this->assertEquals($this->object, $this->object->exactlyOnce());
    $this->assertEquals($this->object->qos->qos, QoS::EXACTLY_ONCE);
  }

  public function testSetTopicChangesProperty() {
    $topic = '#topic';
    $this->object->topic($topic);
    $this->assertEquals($topic, $this->object->topic);
  }

  public function testSetContentChangesProperty() {
    $content = '#content';
    $this->object->content($content);
    $this->assertEquals($content, $this->object->content);
  }

  public function testSetRetainChangesProperty() {
    $retain = true;
    $this->object->retain($retain);
    $this->assertEquals($retain, $this->object->isRetain);
  }

  public function testSetDupChangesProperty() {
    $retain = true;
    $this->object->dup($retain);
    $this->assertEquals($retain, $this->object->isDup);
  }

  public function testSetHandlerChangesProperty() {
    $handler = new class implements \Mqtt\Client\Handler\IMessage {
      public function onMessageAcknowledged(Message $message): void {}
      public function onMessageSent(Message $message): void {}
      public function onMessageUnacknowledged(Message $message): void {}
    };
    $this->object->handler($handler);
    $this->assertEquals($handler, $this->object->handler);
  }

  public function testSetTopicFluently() {
    $this->assertSame($this->object, $this->object->topic(''));
  }

  public function testSetContentFluently() {
    $this->assertSame($this->object, $this->object->content(''));
  }

  public function testSetRetainFluently() {
    $this->assertSame($this->object, $this->object->retain());
  }

  public function testSetDupFluently() {
    $this->assertSame($this->object, $this->object->dup());
  }

  public function testSetHandlerFluently() {
    $this->assertSame($this->object, $this->object->handler(new class implements \Mqtt\Client\Handler\IMessage {
      public function onMessageAcknowledged(Message $message): void {}
      public function onMessageSent(Message $message): void {}
      public function onMessageUnacknowledged(Message $message): void {}
    }));
  }

}
