<?php

namespace Mqtt\Entity;

class MessageTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $qosMock;

  /**
   * @var Message
   */
  protected $object;

  protected function setUp() {
    $this->qosMock = $this->getMockBuilder(\Mqtt\Entity\QoS::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Message($this->qosMock);
  }

  public function testQosSetsOwner() {
    $this->qosMock->
      expects($this->once())->
      method('setRelated')->
      with($this->equalTo($this->object));

    $this->object = new Message($this->qosMock);
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

  public function testSetTopicFluently() {
    $this->assertSame($this->object, $this->object->topic(''));
  }

  public function testSetContentFluently() {
    $this->assertSame($this->object, $this->object->content(''));
  }

  public function testSetRetainFluently() {
    $this->assertSame($this->object, $this->object->retain());
  }

}
