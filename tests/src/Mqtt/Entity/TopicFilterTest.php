<?php

namespace Mqtt\Entity;

class TopicFilterTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $qosMock;

  /**
   * @var TopicFilter
   */
  protected $object;

  protected function setUp() {
    $this->qosMock = $this->getMockBuilder(\Mqtt\Entity\QoS::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new TopicFilter($this->qosMock);
  }

  public function testMatchingExactTopicFilter() {
    $topic = '/topic/1/2';
    $this->object->filter($topic);

    $this->assertTrue($this->object->isMatching($topic));

    $this->assertFalse($this->object->isMatching('/topic/1/2/3'));
    $this->assertFalse($this->object->isMatching('/topic/1'));
    $this->assertFalse($this->object->isMatching('/topic/'));
    $this->assertFalse($this->object->isMatching('/'));
  }

  public function testMatchingMultilevelLevelTopicFilter() {
    $topic = '/topic/1/2/3/4';
    $this->object->filter($topic);

    $this->assertTrue($this->object->isMatching('/topic/#/2/3/4'));
    $this->assertTrue($this->object->isMatching('/topic/#/#/3/4'));
    $this->assertTrue($this->object->isMatching('/topic/#/3/4'));
    $this->assertTrue($this->object->isMatching('/topic/#/#/#/4'));
    $this->assertTrue($this->object->isMatching('/topic/#/#/4'));
    $this->assertTrue($this->object->isMatching('/topic/#/4'));
    $this->assertTrue($this->object->isMatching('/topic/#/2/3/#'));
    $this->assertTrue($this->object->isMatching('/topic/#/#/3/#'));
    $this->assertTrue($this->object->isMatching('/topic/#/3/#'));
    $this->assertTrue($this->object->isMatching('/topic/#/#/#/#'));
    $this->assertTrue($this->object->isMatching('/topic/#/#/#'));
    $this->assertTrue($this->object->isMatching('/topic/#/#'));
    $this->assertTrue($this->object->isMatching('/topic/#'));
    $this->assertTrue($this->object->isMatching('#'));

    $this->assertFalse($this->object->isMatching('/other-topic/#/2/3/4'));
    $this->assertFalse($this->object->isMatching('/other-topic/#/3/4'));
    $this->assertFalse($this->object->isMatching('/other-topic/#/4'));
    $this->assertFalse($this->object->isMatching('/other-topic/#'));
    $this->assertFalse($this->object->isMatching('/topic/#/#/3'));
    $this->assertFalse($this->object->isMatching('/topic/#/3'));

    $this->assertFalse($this->object->isMatching('/2/+/topic'));
  }

  public function testMatchingSingleLevelTopicFilter() {
    $topic = '/topic/1/2/3/4';
    $this->object->filter($topic);

    $this->assertTrue($this->object->isMatching('/topic/+/2/3/4'));
    $this->assertTrue($this->object->isMatching('/topic/+/+/3/4'));
    $this->assertTrue($this->object->isMatching('/topic/+/+/+/4'));
    $this->assertTrue($this->object->isMatching('/topic/+/+/+/+'));
    $this->assertTrue($this->object->isMatching('/+/+/+/+/+'));

    $this->assertFalse($this->object->isMatching('/topic/+/1/2/3/4'));
    $this->assertFalse($this->object->isMatching('/topic/+/3/4'));
    $this->assertFalse($this->object->isMatching('/topic/+/+/4'));
    $this->assertFalse($this->object->isMatching('/topic/+/+/+'));
    $this->assertFalse($this->object->isMatching('/+/+/+/+'));
    $this->assertFalse($this->object->isMatching('/+/+/+/+/+/+'));
  }

  public function testQosSetsOwner() {
    $this->qosMock->
      expects($this->once())->
      method('setRelated')->
      with($this->equalTo($this->object));

    $this->object = new TopicFilter($this->qosMock);
  }

  public function testAtMostOnceFluentlyProxiesCall() {
    $this->proxy($this->object)->
      with($this->qosMock)->
      method('atMostOnce')->
      proxyReturnValue($this->object)->
      assert();
  }

  public function testAtLeastOnceFluentlyProxiesCall() {
    $this->proxy($this->object)->
      with($this->qosMock)->
      method('atLeastOnce')->
      proxyReturnValue($this->object)->
      assert();
  }

  public function testExactlyOnceFluentlyProxiesCall() {
    $this->proxy($this->object)->
      with($this->qosMock)->
      method('exactlyOnce')->
      proxyReturnValue($this->object)->
      assert();
  }

  public function testSetNameChangesProperty() {
    $name = '#name';
    $this->object->filter($name);
    $this->assertEquals($name, $this->object->filter);
  }

  public function testSetNameFluently() {
    $this->assertSame($this->object, $this->object->filter(''));
  }

}
