<?php

namespace Mqtt\Protocol;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
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
    $this->connectionMock = $this->getMockBuilder(\Mqtt\Connection\IConnection::class)->
      disableOriginalConstructor()->
      getMock();

    $this->sessionMock = $this->getMockBuilder(\Mqtt\Protocol\IHandler::class)->
      disableOriginalConstructor()->
      getMock();

    /**
     * @HINT Partially mocking of SUT so clone $frame is disabled in Test context
     */
    $this->object = new \Mqtt\Protocol\Protocol(
      $this->connectionMock,
      $this->___container->get(\Mqtt\Protocol\Binary\Frame::class),
      $this->___container->get(\Mqtt\Protocol\Packet\Factory::class)
    );

    $this->object->setSession($this->sessionMock);
  }

  public function testReceivedPacketIfForwardedToSessionHandler() {
    $stream = $this->toArrayStream(0xc0, 0x00);

    $expectedPacket = $this->object->createPacket(\Mqtt\Protocol\IPacket::PINGREQ);

    $this->sessionMock->
      expects($this->once())->
      method('onPacketReceived')->
      with($this->equalTo($expectedPacket));

    $this->object->read($stream);
  }

  public function testWriteEncodesPacketProperly() {
    $packet = $this->object->createPacket(\Mqtt\Protocol\IPacket::PINGREQ);

    $this->connectionMock->
      expects($this->once())->
      method('write')->
      with($this->equalTo($this->toStringStream(0xc0, 0x00)));

    $this->object->writePacket($packet);
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

}
