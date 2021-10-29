<?php

namespace Mqtt\Protocol\Packet\Type;

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

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::PUBLISH));
    $this->assertFalse($this->object->is(0));
  }

  public function testDecodeSetsProperPacketType() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object->decode($frameMock);
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::PUBLISH));
  }

  public function testDecodeSetsProperRetain() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->any())->
      method('isRetain')->
      will($this->returnValue(true));


    $this->object->decode($frameMock);
    $this->assertTrue($this->object->retain);
  }

  public function testDecodeSetsProperDup() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->any())->
      method('isDup')->
      will($this->returnValue(true));


    $this->object->decode($frameMock);
    $this->assertTrue($this->object->dup);
  }

  public function testDecodeSetsProperQos() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $qos = \Mqtt\Entity\IQoS::EXACTLY_ONCE;

    $frameMock->
      expects($this->any())->
      method('getQos')->
      will($this->returnValue($qos));


    $this->object->decode($frameMock);
    $this->assertEquals($qos, $this->object->qos);
  }

  public function testDecodeSetsProperId() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $qos = \Mqtt\Entity\IQoS::EXACTLY_ONCE;
    $id = 117;

    $frameMock->
      expects($this->any())->
      method('getQos')->
      will($this->returnValue($qos));

    $frameMock->
      expects($this->any())->
      method('getWord')->
      will($this->returnValue($id));


    $this->object->decode($frameMock);
    $this->assertEquals($id, $this->object->id);
  }

  public function testDecodeSetsProperContent() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $payload = '#Payload';

    $frameMock->
      expects($this->any())->
      method('getPayload')->
      will($this->returnValue($payload));

    $this->object->decode($frameMock);
    $this->assertEquals($payload, $this->object->content);
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
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::PUBLISH));

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
      method('setAsDup')->
      with($this->equalTo($this->object->dup));

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
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::PUBLISH));

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
      method('setAsDup')->
      with($this->equalTo($this->object->dup));

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
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::PUBLISH));

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
      method('setAsDup')->
      with($this->equalTo($this->object->dup));

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
