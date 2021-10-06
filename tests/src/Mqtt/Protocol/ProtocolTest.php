<?php

namespace Mqtt\Protocol;

class ProtocolTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;
  use \Test\Helpers\Binary;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $connectionMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $frameMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $packetFactoryMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $sessionMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $packetMock;

  /**
   * @var Protocol
   */
  protected $object;

  protected function setUp() {
    $this->frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalClone()->
      disableOriginalConstructor()->
      getMock();

    $this->connectionMock = $this->getMockBuilder(\Mqtt\Connection\IConnection::class)->
      disableOriginalConstructor()->
      getMock();

    $this->packetFactoryMock = $this->getMockBuilder(\Mqtt\Protocol\Packet\Factory::class)->
      disableOriginalConstructor()->
      getMock();

    $this->packetMock = $this->getMockBuilder(\Mqtt\Protocol\IPacket::class)->
      disableOriginalConstructor()->
      getMock();

    $this->sessionMock = $this->getMockBuilder(\Mqtt\Protocol\IHandler::class)->
      disableOriginalConstructor()->
      getMock();

    /**
     * @HINT Partially mocking of SUT so clone $frame is disabled in Test context
     */
    $this->object = $this->getMockBuilder(\Mqtt\Protocol\Protocol::class)->
      setConstructorArgs([$this->connectionMock, $this->frameMock, $this->packetFactoryMock])->
      setMethods([ 'getNewFrame' ])->
      getMock();

    $this->object->
      expects($this->any())->
      method('getNewFrame')->
      will($this->returnValue($this->frameMock));

    $this->object->setSession($this->sessionMock);
  }

  public function testReadDecodesPacketProperly() {
    $stream = $this->toArrayStream(0x1, 0x2, 0x5);

    $this->packetMock->
      expects($this->once())->
      method('decode')->
      with($this->equalTo($this->frameMock));

    $this->frameMock->
      expects($this->once())->
      method('fromStream')->
      with($this->equalTo($stream));

    $this->packetFactoryMock->
      expects($this->any())->
      method('create')->
      will($this->returnValue($this->packetMock));

    $this->sessionMock->
      expects($this->once())->
      method('onPacketReceived')->
      with($this->equalTo($this->packetMock));

    $this->object->read($stream);
  }

  public function testWriteEncodesPacketProperly() {
    $this->frameMock->
      expects($this->any())->
      method('__toString')->
      will($this->returnValue('#frame'));

    $this->packetMock->
      expects($this->once())->
      method('encode')->
      with($this->equalTo($this->frameMock));

    $this->connectionMock->
      expects($this->once())->
      method('write')->
      with($this->equalTo((string)$this->frameMock));

    $this->object->writePacket($this->packetMock);
  }

  public function testConnectProxiesToConnection() {
    $this->proxy($this->object)->
      with($this->connectionMock)->
      method('connect')->
      proxyMethod('establish')->
      assert();
  }

  public function testDisonnectProxiesToConnection() {
    $this->proxy($this->object)->
      with($this->connectionMock)->
      method('disconnect')->
      proxyMethod('disconnect')->
      assert();
  }

  public function testOnConnectProxiesToSession() {
    $this->proxy($this->object)->
      with($this->sessionMock)->
      method('onConnect')->
      assert();
  }

  public function testOnTickProxiesToSession() {
    $this->proxy($this->object)->
      with($this->sessionMock)->
      method('onTick')->
      assert();
  }

  public function testOnDisconnectProxiesToSession() {
    $this->proxy($this->object)->
      with($this->sessionMock)->
      method('onDisconnect')->
      assert();
  }

  public function testCreatePacketProxiesToPacketFactory() {
    $this->proxy($this->object)->
      with($this->packetFactoryMock)->
      method('createPacket')->
      proxyMethod('create')->
      arguments([4])->
      assert();
  }

  public function testGetNewFrameClonesFrame() {
    $this->object = new Protocol($this->connectionMock, $this->frameMock, $this->packetFactoryMock);
    $this->assertEquals($this->frameMock, $this->object->getNewFrame());
    $this->assertNotSame($this->frameMock, $this->object->getNewFrame());
  }

}
