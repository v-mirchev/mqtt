<?php

namespace Mqtt\Protocol\Packet\Type;

class PingRespTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\TestPassedAssert;

  /**
   * @var PingResp
   */
  protected $object;

  protected function setUp() {
    $this->object = new PingResp();
  }

  public function testIsA() {
    $this->assertTrue($this->object->is(\Mqtt\Protocol\Packet\IType::PINGRESP));
    $this->assertFalse($this->object->is(0));
  }

  public function testDecodeSuccess() {
    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object->decode($frameMock);

    $this->pass();
  }

}
