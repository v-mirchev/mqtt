<?php

namespace Mqtt\Protocol\Packet;

class SubAckTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var SubAck
   */
  protected $object;

  protected function setUp() {
    $this->object = new SubAck();
  }

  public function testDecodeSetsProperPacketType() {
    $actualId = 111;

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->once())->
      method('getVariableHeaderIdentifier')->
      will($this->returnValue($actualId));

    $this->object->decode($frameMock);
    $this->assertEquals($actualId, $this->object->getId());
  }

  public function testDecodeSetsProperlyReturnCodes() {
    $returnCodes = [ 1, 11, 17 ];

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->once())->
      method('getVariableHeaderIdentifier')->
      will($this->returnValue(111));

    $frameMock->
      expects($this->once())->
      method('getPayloadBytes')->
      will($this->returnValue($returnCodes));

    $this->object->decode($frameMock);
    $this->assertEquals($returnCodes, $this->object->getReturnCodes());
  }

}