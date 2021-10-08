<?php

namespace Mqtt\Protocol\Binary;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface ___$container
 */
class FrameTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;
  use \Test\Helpers\Binary;

  /**
   * @var Frame
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Binary\Frame::class);
  }

  public function testCloneResetsInstance() {
    $this->object->addByte(0x05);
    $object = clone $this->object;
    $this->assertEquals($this->stringToStringStream('0000'), (string) $object);
  }

  public function testEncodingFixedHeaderOnly() {
    $this->object->setAsDup();
    $this->assertEquals($this->stringToStringStream('0800'), (string) $this->object);
  }

  public function testEncodingFixedHeaderSingleVariableHeaderByte() {
    $this->object->setAsDup();
    $this->object->addByte(5);
    $this->assertEquals($this->stringToStringStream('080105'), (string) $this->object);
  }

  public function testEncodingFixedHeaderSingleVariableHeaderString() {
    $this->object->setAsDup();
    $this->object->addString('ABC');
    $this->assertEquals($this->stringToStringStream('08050003414243'), (string) $this->object);
  }

  public function testEncodingFixedHeaderSingleVariableHeaderIdentifier() {
    $this->object->setAsDup();
    $this->object->addWord(67);
    $this->assertEquals($this->stringToStringStream('08020043'), (string) $this->object);
  }

  public function testEncodingFixedHeaderMultipleVariableHeaders() {
    $this->object->setAsDup();
    $this->object->addString('AB');
    $this->object->addByte(06);
    $this->object->addWord(45);

    $this->assertEquals($this->stringToStringStream('08070002414206002d'), (string)$this->object);
  }

  public function testEncodingFixedHeaderMultipleVariableHeadersAndBody() {
    $this->object->addString('AB');
    $this->object->addByte(0x06);
    $this->object->addWord(45);
    $this->object->setPayload('#Payload');

    $this->assertEquals($this->stringToStringStream('000f0002414206002d235061796c6f6164'), (string)$this->object);
  }

  public function testDecodeReadsDupFixedHeaderOnly() {
    $this->object->decode($this->toArrayStream(0x08, 0x00));
    $this->assertTrue($this->object->isDup());
  }

  public function testDecodeReadsDupPacketTypeProperlyFixedHeaderOnly() {
    $this->object->decode($this->toArrayStream(0x28, 0x00));
    $this->assertTrue($this->object->isDup());
    $this->assertEquals(\Mqtt\Protocol\IPacket::CONNACK, $this->object->getPacketType());
  }

  public function testDecodeReadsDupPacketTypeProperlySingleVariableString() {
    $this->object->decode($this->toArrayStream(0x28, 0x05, 0x00, 0x03, 0x41, 0x42, 0x43));
    $this->assertTrue($this->object->isDup());
    $this->assertEquals(\Mqtt\Protocol\IPacket::CONNACK, $this->object->getPacketType());
    $this->assertEquals('ABC', $this->object->getString());
  }

}
