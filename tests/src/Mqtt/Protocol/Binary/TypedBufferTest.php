<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Binary;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class TypedBufferTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Binary\TypedBuffer
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Binary\Buffer
   */
  protected $buffer;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Binary\TypedBuffer::class);
    $this->buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
    $this->object->decorate($this->buffer);
  }

  public function testUint8EncodesProperly() {
    $this->object->appendUint8(0x12);
    $this->assertEquals($this->hex2string('12'), $this->buffer->getString());
  }

  public function testUint16EncodesProperly() {
    $this->object->appendUint16(0xFFEE);
    $this->assertEquals($this->hex2string('FF EE'), $this->buffer->getString());
  }

  public function testUtf8StringEncodesProperly() {
    $this->object->appendUtf8String('ABC');
    $this->assertEquals($this->hex2string('00 03') . 'ABC', $this->buffer->getString());
  }

  public function testUint8DecodesProperly() {
    $this->buffer->set($this->hex2string('12'));
    $this->assertEquals(0x12, $this->object->getUint8()->get());
  }

  public function testUint16DecodesProperly() {
    $this->buffer->set($this->hex2string('12 3F'));
    $this->assertEquals(0x123F, $this->object->getUint16()->get());
  }

  public function testUtf8StringDecodesProperly() {
    $this->buffer->set($this->hex2string('00 03') . 'ABC');
    $this->assertEquals('ABC', $this->object->getUtf8String()->get());
  }

}
