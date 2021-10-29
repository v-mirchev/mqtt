<?php

namespace Mqtt\Protocol\Packet\Type;

class PubCompTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\CallSequenceAssert;

  /**
   * @var PubComp
   */
  protected $object;

  protected function setUp() {
    $this->object = new PubComp();
  }

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::PUBCOMP));
    $this->assertFalse($this->object->is(0));
  }

  public function testDecodeSetsProperPacketType() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object->decode($frameMock);
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::PUBCOMP));
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
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::PUBCOMP));

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
