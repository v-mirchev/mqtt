<?php

namespace Mqtt\Protocol\Packet\Type;

class PingReqTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\CallSequenceAssert;

  /**
   * @var PingReq
   */
  protected $object;

  protected function setUp() {
    $this->object = new PingReq();
  }

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::PINGREQ));
    $this->assertFalse($this->object->is(0));
  }

  public function testEncodeSetsProperPacketType() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->callSequence()->start();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::PINGREQ));

    $frameMock->
      expects($this->callSequence()->next())->
      method('setQos')->
      with($this->equalTo(\Mqtt\Entity\IQoS::AT_MOST_ONCE));

    $this->object->encode($frameMock);
  }

}