<?php

namespace Mqtt\Protocol\Packet\Type;

class SubAckTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var SubAck
   */
  protected $object;

  protected function setUp() {
    $this->object = new SubAck();
  }

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::SUBACK));
    $this->assertFalse($this->object->is(0));
  }

  public function testDecodeSetsProperPacketType() {
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

  public function testDecodeSetsProperlyReturnCodes() {
    $returnCodes = [ 1, 11, 17 ];

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->once())->
      method('getWord')->
      will($this->returnValue(111));

    $frameMock->
      expects($this->once())->
      method('getPayloadBytes')->
      will($this->returnValue($returnCodes));

    $this->object->decode($frameMock);
    $this->assertEquals($returnCodes, $this->object->getReturnCodes());
  }

}
