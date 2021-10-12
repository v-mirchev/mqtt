<?php

namespace Mqtt\Protocol\Packet\Type;

class UnsubscribeTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\CallSequenceAssert;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $frameMock;

  /**
   * @var Unsubscribe
   */
  protected $object;

  protected function setUp() {
    $this->frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Unsubscribe();
  }

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::UNSUBSCRIBE));
    $this->assertFalse($this->object->is(0));
  }

  public function testEncodeAddsToFrameWithProperOrder() {

    $this->object->id = 112;
    $this->object->topics = [
      (new \Mqtt\Entity\Topic(new \Mqtt\Entity\QoS()))->atMostOnce()->name('#topic1'),
      (new \Mqtt\Entity\Topic(new \Mqtt\Entity\QoS()))->atLeastOnce()->name('#topic2'),
      (new \Mqtt\Entity\Topic(new \Mqtt\Entity\QoS()))->exactlyOnce()->name('#topic3'),
    ];

    $this->callSequence()->start();

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::UNSUBSCRIBE));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addWord')->
      with($this->equalTo($this->object->id));

    foreach ($this->object->topics as $topic) {
      $frameMock->
        expects($this->callSequence()->next())->
        method('addString')->
        with($this->equalTo($topic->name));
    }

    $this->object->encode($frameMock);
  }

}
