<?php

namespace Mqtt\Protocol\Binary;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class FrameTest extends \PHPUnit\Framework\TestCase {

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

  public function testEncodingFixedHeaderDupOnly() {
    $this->object->setAsDup();
    $this->assertEquals($this->stringToStringStream('0800'), (string) $this->object);
  }

  public function testEncodingFixedHeaderRetainOnly() {
    $this->object->setAsRetain();
    $this->assertEquals($this->stringToStringStream('0100'), (string) $this->object);
  }

  public function testEncodingFixedHeaderQoSOnly() {
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE);
    $this->assertEquals($this->stringToStringStream('0200'), (string) $this->object);
    $this->object->setQoS(\Mqtt\Entity\IQoS::EXACTLY_ONCE);
    $this->assertEquals($this->stringToStringStream('0400'), (string) $this->object);
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE);
    $this->assertEquals($this->stringToStringStream('0000'), (string) $this->object);
  }

  public function testEncodingFixedHeaderReservedOnly() {
    $this->object->setReserved(0x2);
    $this->assertEquals($this->stringToStringStream('0200'), (string) $this->object);
    $this->object->setReserved(0x1);
    $this->assertEquals($this->stringToStringStream('0100'), (string) $this->object);
    $this->object->setReserved(0x0);
    $this->assertEquals($this->stringToStringStream('0000'), (string) $this->object);
  }

  public function testEncodingFixedHeaderSingleVariableHeaderByte() {
    $this->object->setPacketType(\Mqtt\Protocol\Packet\IType::PINGREQ);
    $this->object->setAsDup();
    $this->object->addByte(5);
    $this->assertEquals($this->stringToStringStream('c80105'), (string) $this->object);
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

  public function testDecodingFixedHeaderQosOnly() {
    $this->object->decode($this->toArrayStream(0x04, 0x00));
    $this->assertEquals(\Mqtt\Entity\IQoS::EXACTLY_ONCE, $this->object->getQoS());
    $this->object->decode($this->toArrayStream(0x02, 0x00));
    $this->assertEquals(\Mqtt\Entity\IQoS::AT_LEAST_ONCE, $this->object->getQoS());
    $this->object->decode($this->toArrayStream(0x00, 0x00));
    $this->assertEquals(\Mqtt\Entity\IQoS::AT_MOST_ONCE, $this->object->getQoS());
  }

  public function testDecodeReadsDupFixedHeaderOnly() {
    $this->object->decode($this->toArrayStream(0x08, 0x00));
    $this->assertTrue($this->object->isDup());
  }

  public function testDecodeReadsDupProperlyFixedHeaderOnly() {
    $this->object->decode($this->toArrayStream(0x28, 0x00));
    $this->assertTrue($this->object->isDup());
    $this->assertEquals(\Mqtt\Protocol\Packet\IType::CONNACK, $this->object->getPacketType());
  }

  public function testDecodeReadsDupProperlySingleVariableString() {
    $this->object->decode($this->toArrayStream(0x28, 0x05, 0x00, 0x03, 0x41, 0x42, 0x43));
    $this->assertTrue($this->object->isDup());
    $this->assertEquals(\Mqtt\Protocol\Packet\IType::CONNACK, $this->object->getPacketType());
    $this->assertEquals('ABC', $this->object->getString());
  }

  public function testDecodeReadsRetainProperlyFixedHeaderOnly() {
    $this->object->decode($this->toArrayStream(0x21, 0x0));
    $this->assertTrue($this->object->isRetain());
    $this->assertEquals(\Mqtt\Protocol\Packet\IType::CONNACK, $this->object->getPacketType());
  }

  public function testDecodeFixedHeaderSingleVariableHeaderIdentifier() {
    $this->object->decode($this->toArrayStream(0x08, 0x02, 0x00, 0x43));
    $this->assertEquals(67, $this->object->getWord());
  }

  public function testDecodeFixedHeaderSingleVariableHeaderByte() {
    $this->object->decode($this->toArrayStream(0xc8, 0x01, 0x05));
    $this->assertEquals(5, $this->object->getByte());
  }

  public function testDecodePayloadAfterFetchingOtherVariables() {
    $this->object->decode($this->toArrayStream(
      0x00, 0x0f, 0x00, 0x02, 0x41, 0x42, 0x06, 0x00, 0x2d, 0x23, 0x50, 0x61, 0x79, 0x6c, 0x6f, 0x61, 0x64
    ));

    $this->object->getString();
    $this->object->getByte();
    $this->object->getWord();
    $this->assertEquals('#Payload', $this->object->getPayload());
  }

  public function testDecodePayloadBytesAfterFetchingOtherVariables() {
    $this->object->decode($this->toArrayStream(
      0x00, 0x0f, 0x00, 0x02, 0x41, 0x42, 0x06, 0x00, 0x2d, 0x23, 0x50, 0x61, 0x79, 0x6c, 0x6f, 0x61, 0x64
    ));

    $this->object->getString();
    $this->object->getByte();
    $this->object->getWord();
    $this->assertEquals(
      [ord('#'), ord('P'), ord('a'), ord('y'), ord('l'), ord('o'), ord('a'), ord('d')],
      $this->object->getPayloadBytes()
    );
  }

}
