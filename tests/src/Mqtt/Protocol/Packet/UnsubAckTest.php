<?php

namespace Mqtt\Protocol\Packet;

class UnsubAckTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var UnsubAck
   */
  protected $object;

  protected function setUp() {
    $this->object = new UnsubAck();
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
    $this->assertEquals($actualId, $this->object->getId());
  }

}
