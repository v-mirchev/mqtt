<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Binary\Data;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class Uint8Test extends \PHPUnit\Framework\TestCase {

  /**
   * @var Uint8
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
  }

  public function testSetLimitsTo8bitSize() {
    $this->object->set(0xFFFF);
    $this->assertEquals(0xFF, $this->object->get());
    $this->object->set(0xFF00);
    $this->assertEquals(0x00, $this->object->get());
  }

  public function testSetFromChar() {
    $this->object->set('A');
    $this->assertEquals(0x41, $this->object->get());
  }

  public function testSetFromUint() {
    $uint8 = clone $this->object;
    $uint8->set(0x1F);

    $this->object->set($uint8);
    $this->assertEquals(0x1F, $this->object->get());
  }

  public function testStringable() {
    $this->object->set(0x41);
    $this->assertEquals('A', (string)$this->object);
  }

  public function testBitsOperateOnObjectValue() {
    $this->object->set(0xFF);
    $this->object->bits()->setBit(3, 0);
    $this->assertEquals(0xF7, $this->object->get());

    $this->object->set(0x00);
    $this->object->bits()->setBit(3, 1);
    $this->assertEquals(0x08, $this->object->get());
  }

  public function testDecodesBufferProperly() {
    /* @var $buffer \Mqtt\Protocol\Binary\IBuffer */
    $buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
    $buffer->set(chr(0x1F));

    $this->object->set(0x00);
    $this->object->decode($buffer);
    $this->assertEquals(0x1F, $this->object->get());
  }

  public function testEncodesBufferProperly() {
    /* @var $buffer \Mqtt\Protocol\Binary\IBuffer */
    $buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);

    $this->object->set(0x1F);
    $this->object->encode($buffer);
    $this->assertEquals(chr(0x1F), $buffer->getString());
  }

}
