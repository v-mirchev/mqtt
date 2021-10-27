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
    $this->object->subscriptions = [
      (new \Mqtt\Entity\Subscription(new \Mqtt\Entity\TopicFilter(new \Mqtt\Entity\QoS())))->atMostOnce()->topicFilter('#topic1'),
      (new \Mqtt\Entity\Subscription(new \Mqtt\Entity\TopicFilter(new \Mqtt\Entity\QoS())))->atLeastOnce()->topicFilter('#topic2'),
      (new \Mqtt\Entity\Subscription(new \Mqtt\Entity\TopicFilter(new \Mqtt\Entity\QoS())))->exactlyOnce()->topicFilter('#topic3'),    ];

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
      method('setReserved')->
      with($this->equalTo(0x2));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addWord')->
      with($this->equalTo($this->object->id));

    foreach ($this->object->subscriptions as $subscription) {
      $frameMock->
        expects($this->callSequence()->next())->
        method('addString')->
        with($this->equalTo($subscription->topicFilter->filter));
    }

    $this->object->encode($frameMock);
  }

}
