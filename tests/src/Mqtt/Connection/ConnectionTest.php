<?php

namespace Mqtt\Connection;

class ConnectionTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\TestPassedAssert;

  /**
   * @var Connection
   */
  protected $object;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $socketMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $protocolMock;

  protected function setUp() {
    $this->socketMock = $this->getMockBuilder(\Mqtt\Connection\Socket::class)->
      disableOriginalConstructor()->
      getMock();

    $this->protocolFrameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      disableOriginalClone()->
      getMock();

    $this->protocolMock = $this->getMockBuilder(\Mqtt\Connection\IHandler::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Connection($this->socketMock, $this->protocolFrameMock);
    $this->object->setProtocol($this->protocolMock);
  }

  public function testStreamWillThrowExceptionIfSocketDies() {
    $this->socketMock->
      method('isAlive')->
      will($this->returnValue(true));

    $this->socketMock->
      method('read')->
      will($this->throwException(new \Exception()));

    $this->expectException(\Exception::class);
    foreach ($this->object->stream() as $byte) {}
  }

  public function testStreamWillReturnProperResult() {
    $inputContent = [ 'A', 'B', 'C', null, null, 'D' ];
    $outputContent = array_filter($inputContent);

    $isAliveValues = array_fill(0, count($inputContent), true);
    $isAliveValues[] = false;

    $this->socketMock->
      method('isAlive')->
      will(call_user_func_array([$this, 'onConsecutiveCalls'], $isAliveValues));

    $this->socketMock->
      method('getByte')->
      will($this->onConsecutiveCalls('A', 'B', 'C', null, null, 'D'));

    foreach ($this->object->stream() as $byte) {
      $this->assertEquals(array_shift($outputContent), $byte);
    }
  }

  public function testEstablishWillThrowExceptionIfSocketDies() {
    $this->socketMock->
      method('isAlive')->
      will($this->returnValue(true));

    $this->protocolMock->
      method('read')->
      will($this->throwException(new \Exception()));

    $this->expectException(\Exception::class);
    $this->object->establish();
  }

  public function testConnectionWillNotThrowExceptionIfDisconnectingGracefully() {
    $this->socketMock->
      method('isAlive')->
      will($this->returnValue(true));

    $this->protocolMock->
      method('read')->
      will($this->returnCallback(function () {
        $this->object->disconnect();
        throw new \Exception();
      }));

    $this->object->establish();

    $this->assertNoExceptionThrown();
  }

  public function testProtocolOnTickCalledContinouslyWhileSocketIsAlive() {
    $expectedCalls = 3;
    $this->socketMock->
      method('isAlive')->
      will($this->onConsecutiveCalls(true, true, true, false));

      $this->protocolMock->
        expects($this->exactly($expectedCalls))->
        method('onTick');

    foreach ($this->object->stream() as $byte) {}
  }

  public function testProtocolReadCalledContinouslyWhileSocketIsAlive() {
    $expectedCalls = 3;
    $this->socketMock->
      method('isAlive')->
      will($this->onConsecutiveCalls(true, true, true, false));

      $this->protocolMock->
        expects($this->exactly($expectedCalls))->
        method('read');

      $this->object->establish();
  }

  public function testWriteUsesSocket() {
    $this->socketMock->
      expects($this->once())->
      method('write')->
      with($this->equalTo($this->protocolFrameMock));

    $this->object->write($this->protocolFrameMock);
  }

  public function testDisconnectUsesSocket() {
    $this->socketMock->
      expects($this->once())->
      method('disconnect');

    $this->object->disconnect();
  }

}
