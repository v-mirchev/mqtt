<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Binary\Data;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class Utf8StringTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var Utf8String
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Utf8String::class);
  }

  public function testStringable() {
    $this->object->set('ABC');
    $this->assertEquals($this->hex2string('00 03') . 'ABC', (string)$this->object);
  }

  public function testDecodesBufferProperly() {
    /* @var $buffer \Mqtt\Protocol\Binary\IBuffer */
    $buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
    $buffer->set($this->hex2string('00 03') . 'ABC');

    $this->object->set('');
    $this->object->decode($buffer);
    $this->assertEquals('ABC', $this->object->get());
  }

  public function testEncodesBufferProperly() {
    /* @var $buffer \Mqtt\Protocol\Binary\IBuffer */
    $buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);

    $this->object->set('ABC');
    $this->object->encode($buffer);
    $this->assertEquals($this->hex2string('00 03') . 'ABC', $buffer->getString());
  }

}
