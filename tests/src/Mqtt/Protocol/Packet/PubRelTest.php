<?php

namespace Mqtt\Protocol\Packet;

class PubRelTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\CallSequenceAssert;

  /**
   * @var PubRel
   */
  protected $object;

  protected function setUp() {
    $this->object = new PubRel();
  }

  public function testEncodeSetsProperPacketType() {
    $this->object->id = 1134;

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->callSequence()->start();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\IPacket::PUBREL));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addWord')->
      with($this->equalTo($this->object->id));

    $this->object->encode($frameMock);
  }

}
