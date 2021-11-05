<?php

namespace Mqtt\Protocol\Binary\Data;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class Utf8StringTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Utf8String
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Utf8String::class);
  }

  public function testStringable() {
    $this->object->set('ABC');
    $this->assertEquals(chr(0x00) . chr(0x03) . 'ABC', (string)$this->object);
  }

  public function testDecodesBufferProperly() {
    /* @var $buffer \Mqtt\Protocol\Binary\IBuffer */
    $buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
    $buffer->set(chr(0x00) . chr(0x03) . 'ABC');

    $this->object->set('');
    $this->object->decode($buffer);
    $this->assertEquals('ABC', $this->object->get());
  }

  public function testEncodesBufferProperly() {
    /* @var $buffer \Mqtt\Protocol\Binary\IBuffer */
    $buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);

    $this->object->set('ABC');
    $this->object->encode($buffer);
    $this->assertEquals(chr(0x00) . chr(0x03) . 'ABC', $buffer->getString());
  }

}
