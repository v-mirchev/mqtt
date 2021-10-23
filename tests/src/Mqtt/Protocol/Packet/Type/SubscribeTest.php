<?php

namespace Mqtt\Protocol\Packet\Type;

class SubscribeTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\CallSequenceAssert;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $frameMock;

  /**
   * @var Subscribe
   */
  protected $object;

  protected function setUp() {
    $this->frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Subscribe();
  }

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::SUBSCRIBE));
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
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::SUBSCRIBE));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setReserved')->
      with($this->equalTo(0x2));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addWord')->
      with($this->equalTo($this->object->id));

    foreach ($this->object->topics as $topic) {
      $frameMock->
        expects($this->callSequence()->next())->
        method('addString')->
        with($this->equalTo($topic->name));

      $frameMock->
        expects($this->callSequence()->next())->
        method('addByte')->
        with($this->equalTo($topic->qos->qos));
    }

    $this->object->encode($frameMock);
  }

}
