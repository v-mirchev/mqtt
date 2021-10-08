<?php

namespace Mqtt\Protocol\Packet;

class PublishTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;
  use \Test\Helpers\CallSequenceAssert;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $frameMock;

  /**
   * @var Publish
   */
  protected $object;

  protected function setUp() {
    $this->frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Publish();
    $this->callSequence()->start();
  }

  public function testEncodeAddsToFrameWithProperOrderWhenQosSetToAtMostOnce() {

    $this->object->topic = '#topic';
    $this->object->content = '#content';
    $this->object->qos = \Mqtt\Entity\IQoS::AT_MOST_ONCE;
    $this->object->retain = true;
    $this->object->id = 112;

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\IPacket::PUBLISH));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setQos')->
      with($this->equalTo(\Mqtt\Entity\IQoS::AT_MOST_ONCE));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setAsRetain')->
      with($this->equalTo($this->object->retain));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addString')->
      with($this->equalTo($this->object->topic));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPayload')->
      with($this->equalTo($this->object->content));

    $frameMock->
      expects($this->never())->
      method('addWord');

    $this->object->encode($frameMock);
  }

  public function testEncodeAddsToFrameWithProperOrderWhenQosSetToAtLeastOnce() {

    $this->object->topic = '#topic';
    $this->object->content = '#content';
    $this->object->qos = \Mqtt\Entity\IQoS::AT_LEAST_ONCE;
    $this->object->retain = true;
    $this->object->id = 112;

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\IPacket::PUBLISH));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setQos')->
      with($this->equalTo(\Mqtt\Entity\IQoS::AT_LEAST_ONCE));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setAsRetain')->
      with($this->equalTo($this->object->retain));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addString')->
      with($this->equalTo($this->object->topic));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addWord')->
      with($this->equalTo($this->object->id));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPayload')->
      with($this->equalTo($this->object->content));

    $this->object->encode($frameMock);
  }

  public function testEncodeAddsToFrameWithProperOrderWhenQosSetToExactlyOnce() {

    $this->object->topic = '#topic';
    $this->object->content = '#content';
    $this->object->qos = \Mqtt\Entity\IQoS::EXACTLY_ONCE;
    $this->object->retain = true;
    $this->object->id = 112;

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\IPacket::PUBLISH));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setQos')->
      with($this->equalTo(\Mqtt\Entity\IQoS::EXACTLY_ONCE));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setAsRetain')->
      with($this->equalTo($this->object->retain));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addString')->
      with($this->equalTo($this->object->topic));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addWord')->
      with($this->equalTo($this->object->id));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPayload')->
      with($this->equalTo($this->object->content));

    $this->object->encode($frameMock);
  }

}
