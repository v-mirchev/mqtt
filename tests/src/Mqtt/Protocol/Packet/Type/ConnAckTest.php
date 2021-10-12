<?php

namespace Mqtt\Protocol\Packet\Type;

class ConnAckTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $flagsMock;

  /**
   * @var ConnAck
   */
  protected $object;

  protected function setUp() {
    $this->flagsMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Flags\ConnAck::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new ConnAck($this->flagsMock);
  }

  public function testDecodeProxiesFrameBodyToFlags() {
    $body = 0x0001;

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->any())->
      method('getWord')->
      will($this->returnValue($body));

    $this->proxy($this->object)->
      with($this->flagsMock)->
      method('decode')->
      arguments([ $frameMock ])->
      proxyMethod('set')->
      proxyArguments([$body])->
      assert();
  }

  public function testGetReturnCodeProxiesCalls() {
    $this->proxy($this->object)->
      with($this->flagsMock)->
      method('getReturnCode')->
      returnValue(5)->
      assert();
  }

  public function testGetReturnMessageProxiesCalls() {
    $this->proxy($this->object)->
      with($this->flagsMock)->
      method('getReturnCodeMessage')->
      returnValue('Connection Refused, unacceptable protocol version')->
      proxyMethod('getReturnCode')->
      proxyReturnValue(1)->
      assert();
  }

  public function testisSessionPresentProxiesCalls() {
    $this->proxy($this->object)->
      with($this->flagsMock)->
      method('isSessionPresent')->
      proxyMethod('getSessionPresent')->
      returnValue(true)->
      assert();
  }

}
