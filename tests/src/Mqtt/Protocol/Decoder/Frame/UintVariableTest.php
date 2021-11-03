<?php

namespace Mqtt\Protocol\Decoder\Frame;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class UintVariableTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\UintVariable
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Frame\UintVariable::class);
  }

  public function testClonedObjectIsInCleanState() {
    $object = clone $this->object;
    $this->assertEquals(0, $object->get());
  }

  public function testReceiverIsNotAffectedByNullValues() {
    $receiver = $this->object->receiver();
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $this->assertFalse($receiver->isCompleted());
  }

  public function testDecodeThrowsExceptionOnLengthLimitExceeded() {
    $receiver = $this->object->receiver();

    $this->expectException(\Exception::class);
    foreach ([ 0xFF, 0xFF, 0xFF, 0xFF, 0x7F,] as $byte) {
      $receiver->input(chr($byte));
    }
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
   * @param int $expectedValue
   * @param int[] $inputBytes
   */
  public function testDecodesProperlyIntegerLength(
    int $expectedValue,
    array $inputBytes
    ) {
    $receiver = $this->object->receiver();
    foreach ($inputBytes as $byte) {
      $receiver->input(chr($byte));
    }

    $this->assertEquals($expectedValue, $this->object->get());
  }

  /**
   * @dataProvider remainingLengthTestDataProvider
   */
  public function testProperlySetsAsCompleted(
    int $expectedValue,
    array $inputBytes
    ) {
    $receiver = $this->object->receiver();
    foreach ($inputBytes as $byte) {
      $receiver->input(chr($byte));
    }

    $this->assertTrue($receiver->isCompleted());
  }

}
