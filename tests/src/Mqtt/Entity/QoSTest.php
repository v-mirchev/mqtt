<?php declare(strict_types = 1);

namespace Mqtt\Entity;

class QoSTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $ownerMock;

  /**
   * @var QoS
   */
  protected $object;

  protected function setUp() {
    $this->ownerMock = $this->getMockBuilder(IQoS::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new QoS();
    $this->object->setRelated($this->ownerMock);
  }

  public function testAtMostOnceSetsQoS() {
    $this->object->atMostOnce();
    $this->assertEquals(\Mqtt\Entity\IQoS::AT_MOST_ONCE, $this->object->qos);
  }

  public function testAtLeastOnceSetsQoS() {
    $this->object->atLeastOnce();
    $this->assertEquals(\Mqtt\Entity\IQoS::AT_LEAST_ONCE, $this->object->qos);
  }

  public function testExactlyOnceSetsQoS() {
    $this->object->exactlyOnce();
    $this->assertEquals(\Mqtt\Entity\IQoS::EXACTLY_ONCE, $this->object->qos);
  }

  public function testAtMostOnceFluentlySetsQoS() {
    $this->assertSame($this->ownerMock, $this->object->atMostOnce());
  }

  public function testAtLeastOnceFluentlySetsQoS() {
    $this->assertSame($this->ownerMock, $this->object->atLeastOnce());
  }

  public function testExactlyOnceFluentlySetsQoS() {
    $this->assertSame($this->ownerMock, $this->object->exactlyOnce());
  }

}
