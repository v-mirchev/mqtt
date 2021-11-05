<?php

namespace Mqtt\Protocol\Binary\Data;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class Uint16Test extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint16::class);
  }

  public function testSetLimitsTo16bitSize() {
    $this->object->set(0xFFFFFFFF);
    $this->assertEquals(0xFFFF, $this->object->get());
    $this->object->set(0xFF0000);
    $this->assertEquals(0x0000, $this->object->get());
  }

  public function testSetFromUint() {
    $uint16 = clone $this->object;
    $uint16->set(0x1F3D);

    $this->object->set($uint16);
    $this->assertEquals(0x1F3D, $this->object->get());
  }

  public function testStringable() {
    $this->object->set(0x4142);
    $this->assertEquals('AB', (string)$this->object);
  }

  public function testBitsOperateOnObjectValue() {
    $this->object->set(0xFFFF);
    $this->object->bits()->setBit(3, 0);
    $this->assertEquals(0xFFF7, $this->object->get());

    $this->object->set(0x0000);
    $this->object->bits()->setBit(3, 1);
    $this->assertEquals(0x0008, $this->object->get());
  }

  public function testDecodesBufferProperly() {
    /* @var $buffer \Mqtt\Protocol\Binary\IBuffer */
    $buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
    $buffer->set(chr(0xA3) . chr(0x1F));

    $this->object->set(0x0000);
    $this->object->decode($buffer);
    $this->assertEquals(0xA31F, $this->object->get());
  }

  public function testEncodesBufferProperly() {
    /* @var $buffer \Mqtt\Protocol\Binary\IBuffer */
    $buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);

    $this->object->set(0xA31F);
    $this->object->encode($buffer);
    $this->assertEquals(chr(0xA3) . chr(0x1F), $buffer->getString());
  }

}
