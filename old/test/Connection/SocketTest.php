<?php declare(strict_types = 1);

namespace Mqtt\Connection;

class SocketTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\TestPassedAssert;
  use \Test\Helpers\TcpServerStub;

  const PORT = 10001;

  /**
   * @var Socket
   */
  protected $object;

  /**
   * @var \phpmock\Mock
   */
  protected $streamSocketRecvFromMock;

  /**
   * @var \phpmock\Mock
   */
  protected $streamSocketSendToMock;

  /**
   * @var \phpmock\Mock
   */
  protected $eofMock;

  /**
   * @var bool
   */
  protected $eofMockedValue;

  public static function setUpBeforeClass() {
    parent::setUpBeforeClass();
    if (!getenv('MUTATION_TESTING')) {
      static::serverStart(static::PORT);
    }
  }

  public static function tearDownAfterClass() {
    if (!getenv('MUTATION_TESTING')) {
      static::serverStop();
    }
    parent::tearDownAfterClass();
  }

  protected function setUp() {
    if (getenv('MUTATION_TESTING')) {
      $this->markTestSkipped('Skipped on mutation testing');
    }

    $builder = new \phpmock\MockBuilder();

    $this->streamSocketRecvFromMock = $builder->setNamespace(__NAMESPACE__)->
      setName('stream_socket_recvfrom')->
      setFunction(function ($socket, int $bytes = null) {
        return false;
    })->build();

    $this->streamSocketSendToMock = $builder->setNamespace(__NAMESPACE__)->
      setName('stream_socket_sendto')->
      setFunction(function ($socket, string $content, int $bytes = null) {
        return false;
    })->build();

    $this->eofMock = $builder->setNamespace(__NAMESPACE__)->
      setName('stream_socket_sendto')->
      setFunction(function ($socket) {
        return $this->eofMockedValue;
    })->build();

    $this->object = new Socket(
      (new \Mqtt\Entity\Configuration\Server())->
        host('127.0.0.1')->
        port(static::PORT)
    );

  }

  protected function tearDown() {
    $this->object->disconnect();
    $this->streamSocketRecvFromMock->disable();
    $this->streamSocketSendToMock->disable();
  }

  public function testIsAlive() {
    $this->assertFalse($this->object->isAlive());

    $this->object->connect();
    $this->assertTrue($this->object->isAlive());

    $this->object->disconnect();
    $this->assertFalse($this->object->isAlive());
  }

  public function testConnectFailurePortUnreachable() {
    $this->object = new Socket(
      (new \Mqtt\Entity\Configuration\Server())
        ->host('127.0.0.1')
        ->port(-1)
    );

    $this->expectException(\Exception::class);
    $this->object->connect();
  }

  public function testConnectFailureCertificateFileNotFound() {
    $this->object = new Socket(
      (new \Mqtt\Entity\Configuration\Server())->
        host('127.0.0.1')->
        port(static::PORT)->
        certificate('aaa')
    );

    $this->expectException(\Exception::class);
    $this->object->connect();
  }

  public function testConnectSuccess() {
    $this->object->connect();
    $this->assertTrue($this->object->isAlive());
  }

  public function testDisconnectSuccess() {
    $this->object->connect();
    $this->object->disconnect();
    $this->assertFalse($this->object->isAlive());
  }

  public function testReconnectSuccess() {
    $this->object->connect();
    $this->object->disconnect();
    $this->object->reconnect();

    $this->assertTrue($this->object->isAlive());
  }

  public function testReconnectDisconnectsOnDirtyState() {
    $this->object = $this->getMockBuilder(\Mqtt\Connection\Socket::class)->
      setConstructorArgs([
        (new \Mqtt\Entity\Configuration\Server())->
        host('127.0.0.1')->
          port(static::PORT)
      ])->
      setMethods([ 'disconnect'])->
      getMock();

    $this->object->connect();
    $this->eofMock->enable();
    $this->eofMockedValue = true;

    $this->object->
      expects($this->once())->
      method('disconnect');

    $this->object->reconnect();
  }

  public function testReadFailureByteNotRead() {
    $this->object->connect();

    $this->streamSocketRecvFromMock->enable();
    $this->expectException(\Throwable::class);

    static::serverWrite('SSH');
    $this->object->read();
  }

  public function testReadFailureSocketDisconnected() {
    $this->expectException(\Exception::class);
    $this->object->read();
  }

  public function testReadSuccess() {
    $this->object->connect();
    static::serverWrite('SSH');
    $this->object->read();
    $this->assertEquals('S', $this->object->getChar());
    $this->object->read();
    $this->assertEquals('S', $this->object->getChar());
    $this->object->read();
    $this->assertEquals('H', $this->object->getChar());
    $this->object->disconnect();

    $this->pass();
  }

  public function testReadEmptyData() {
    $this->object->connect();
    $this->object->read();
    $this->assertNull($this->object->getChar());
    $this->object->disconnect();

    $this->pass();
  }

  public function testWriteFailureNotConnected() {
    $this->expectException(\Exception::class);
    $this->object->write('ABC');
  }

  public function testWriteFailureBytesNotWritten() {
    $this->object->connect();

    $this->streamSocketSendToMock->enable();
    $this->expectException(\Exception::class);
    $this->object->write('ABC');
  }

  public function testWriteSuccess() {
    $this->object->connect();
    $this->object->write('ABC');
    $this->object->disconnect();

    $this->assertNoExceptionThrown();
  }

}
