<?php

namespace Mqtt\Protocol\Packet;

class DisconnectTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Disconnect
   */
  protected $object;

  protected function setUp() {
    $this->object = new Disconnect();
  }

  public function testEncodeSetsProperPacketType() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->once())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\IPacket::DISCONNECT));

    $this->object->encode($frameMock);
  }

}
