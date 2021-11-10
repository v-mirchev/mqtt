<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Frame;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class ControlHeaderTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Frame\ControlHeader
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer $buffer
   */
  protected $buffer;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Frame\ControlHeader::class);
    $this->buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
  }

  public function testEncodesProperlyPacketType() {
    $this->object->setPacketType(\Mqtt\Protocol\IPacketType::PUBACK);

    $expectedOutput = '40';

    $this->object->encode($this->buffer);
    $this->assertEquals($this->hex2string($expectedOutput), (string) $this->buffer);
  }

  public function testEncodesProperlyFlags() {
    $this->object->setFlags(0x02);

    $expectedOutput = '02';

    $this->object->encode($this->buffer);
    $this->assertEquals($this->hex2string($expectedOutput), (string) $this->buffer);
  }

}
