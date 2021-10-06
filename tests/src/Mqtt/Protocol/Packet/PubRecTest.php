<?php

namespace Mqtt\Protocol\Packet;

class PubRecTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var PubRec
   */
  protected $object;

  protected function setUp() {
    $this->object = new PubRec();
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

}
