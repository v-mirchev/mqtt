<?php declare(strict_types = 1);

namespace Mqtt\Entity;

class TopicTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $qosMock;

  /**
   * @var Topic
   */
  protected $object;

  protected function setUp() {
    $this->qosMock = $this->getMockBuilder(\Mqtt\Entity\QoS::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Topic($this->qosMock);
  }

  public function testQosSetsOwner() {
    $this->qosMock->
      expects($this->once())->
      method('setRelated')->
      with($this->equalTo($this->object));

    $this->object = new Topic($this->qosMock);
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
    $this->object->name($name);
    $this->assertEquals($name, $this->object->name);
  }

  public function testSetNameFluently() {
    $this->assertSame($this->object, $this->object->name(''));
  }

}
