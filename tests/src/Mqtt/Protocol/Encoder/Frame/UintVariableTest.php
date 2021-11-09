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
      [0,          [ 0x00 ] ],
      [53,         [ 0x35 ] ],
      [127,        [ 0x7F ] ],
      [128,        [ 0x80, 0x01 ] ],
      [4295,       [ 0xC7, 0x21 ] ],
      [16383,      [ 0xFF, 0x7F ] ],
      [16384,      [ 0x80, 0x80, 0x01 ] ],
      [2097151,    [ 0xFF, 0xFF, 0x7F ] ],
      [2097152,    [ 0x80, 0x80, 0x80, 0x01 ] ],
      [20971520,   [ 0x80, 0x80, 0x80, 0x0a ] ],
      [268435455,  [ 0xFF, 0xFF, 0xFF, 0x7F ] ],
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
    array $expectedBytes
  ) {
    $this->buffer->reset();

    $this->object->set($inputValue);
    $this->object->encode($this->buffer);
    $this->assertEquals($this->toStringStream($expectedBytes), $this->buffer->getString());
  }

}
