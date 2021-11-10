<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Frame;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class UintVariableTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Frame\UintVariable
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer $buffer
   */
  protected $buffer;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Frame\UintVariable::class);
    $this->buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
  }

  public function testDecodeThrowsExceptionOnLengthLimitExceeded() {
    $this->expectException(\Exception::class);
    $this->object->set(\Mqtt\Protocol\Encoder\Frame\UintVariable::MAX_VALUE + 1);
    $this->object->encode($this->buffer);
  }

  public function remainingLengthTestDataProvider() {
    return [
      [0,         '00'],
      [53,        '35'],
      [127,       '7F'],
      [128,       '80 01'],
      [4295,      'C7 21'],
      [16383,     'FF 7F'],
      [16384,     '80 80 01'],
      [2097151,   'FF FF 7F'],
      [2097152,   '80 80 80 01'],
      [20971520,  '80 80 80 0a'],
      [268435455, 'FF FF FF 7F'],
    ];
  }

  /**
   * @dataProvider remainingLengthTestDataProvider
   *
   * @param int $inputValue
   * @param int[] $expectedBytes
   */
  public function testDecodesProperlyIntegerLength(
    int $inputValue,
    string $expectedBytes
  ) {
    $this->buffer->reset();

    $this->object->set($inputValue);
    $this->object->encode($this->buffer);
    $this->assertEquals($this->hex2string($expectedBytes), $this->buffer->getString());
  }

}
