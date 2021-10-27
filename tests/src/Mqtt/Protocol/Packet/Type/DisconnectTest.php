<?php

namespace Mqtt\Protocol\Packet\Type;

class DisconnectTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Disconnect
   */
  protected $object;

  protected function setUp() {
    $this->object = new Disconnect();
  }

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::DISCONNECT));
    $this->assertFalse($this->object->is(0));
  }

  public function testEncodeSetsProperPacketType() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->once())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\Packet\IType::DISCONNECT));

    $frameMock->
      expects($this->once())->
      method('setReserved')->
      with($this->equalTo(0x0));

    $this->object->encode($frameMock);
  }

}
