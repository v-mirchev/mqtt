<?php

namespace Mqtt\Protocol\Packet\Type;

class PubAckTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\CallSequenceAssert;

  /**
   * @var PubAck
   */
  protected $object;

  protected function setUp() {
    $this->object = new PubAck();
  }

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::PUBACK));
    $this->assertFalse($this->object->is(0));
  }

  public function testDecodeSetsProperPacketType() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object->decode($frameMock);
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::PUBACK));
  }

  public function testDecodeSetsProperId() {
    $actualId = 111;

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->once())->
      method('getWord')->
      will($this->returnValue($actualId));

    $this->object->decode($frameMock);
    $this->assertEquals($actualId, $this->object->id);
  }

  public function testEncodeAddsToFrameWithProperOrder() {
    $this->object->id = 111;

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::PUBACK));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setReserved')->
      with($this->equalTo(0x0));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addWord')->
      with($this->equalTo($this->object->id));

    $this->object->encode($frameMock);
  }

}
