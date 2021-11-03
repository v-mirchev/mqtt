<?php

namespace Mqtt\Protocol\Decoder\Frame;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class FixedHeaderTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\FixedHeader
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Frame\FixedHeader::class);
  }

  public function testClonedObjectIsInCleanState() {
    $object = clone $this->object;
    $this->assertEquals(0x00, $object->getFlags()->get());
    $this->assertEquals(0, $object->getPacketType());
    $this->assertEquals(0, $object->getRemainingLength());
  }

  public function testReceiverIsNotAffectedByNullValues() {
    $receiver = $this->object->receiver();
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $this->assertFalse($receiver->isCompleted());
  }

  public function remainingLengthTestDataProvider() {
    return [
      [0,         [0x00, 0x00 ]],
      [53,        [0x00, 0x35 ]],
      [127,       [0x00, 0x7F ]],
      [128,       [0x00, 0x80, 0x01 ]],
      [4295,      [0x00, 0xC7, 0x21 ]],
      [16383,     [0x00, 0xFF, 0x7F ]],
      [16384,     [0x00, 0x80, 0x80, 0x01 ]],
      [2097151,   [0x00, 0xFF, 0xFF, 0x7F ]],
      [2097152,   [0x00, 0x80, 0x80, 0x80, 0x01 ]],
      [20971520,  [0x00, 0x80, 0x80, 0x80, 0x0a ]],
      [268435455, [0x00, 0xFF, 0xFF, 0xFF, 0x7F ]],
    ];
  }

  public function flagsTestDataProvider() {
    return [
      [0x01,         [0xF1, 0x00 ]],
      [0x02,         [0xF2, 0x00 ]],
      [0x03,         [0xF3, 0x00 ]],
      [0x04,         [0xF4, 0x00 ]],
      [0x05,         [0xF5, 0x00 ]],
      [0x06,         [0xF6, 0x00 ]],
      [0x07,         [0xF7, 0x00 ]],
      [0x08,         [0xF8, 0x00 ]],
      [0x09,         [0xF9, 0x00 ]],
      [0x0A,         [0xFA, 0x00 ]],
      [0x0B,         [0xFB, 0x00 ]],
      [0x0C,         [0xFC, 0x00 ]],
      [0x0D,         [0xFD, 0x00 ]],
      [0x0E,         [0xFE, 0x00 ]],
      [0x0F,         [0xFF, 0x00 ]],
    ];
  }

  public function packetTypeDataProvider() {
    return [
      [1,   [0x10, 0x10, 0x44 ]],
      [2,   [0x20, 0x10, 0x44 ]],
      [3,   [0x30, 0x10, 0x44 ]],
      [4,   [0x40, 0x10, 0x44 ]],
      [5,   [0x50, 0x10, 0x44 ]],
      [6,   [0x60, 0x10, 0x44 ]],
      [7,   [0x70, 0x10, 0x44 ]],
      [8,   [0x80, 0x10, 0x44 ]],
      [9,   [0x90, 0x10, 0x44 ]],
      [10,  [0xA0, 0x10, 0x44 ]],
      [11,  [0xB0, 0x10, 0x44 ]],
      [12,  [0xC0, 0x10, 0x44 ]],
      [13,  [0xD0, 0x10, 0x44 ]],
      [14,  [0xE0, 0x10, 0x44 ]],
    ];
  }

  /**
   * @dataProvider packetTypeDataProvider
   */
  public function testPacketTypeProperlyDecoded(
    int $packetType,
    array $encodedBytes
  ) {
    $receiver = $this->object->receiver();
    foreach ($encodedBytes as $byte) {
      $receiver->input(chr($byte));
    }

    $this->assertEquals($packetType, $this->object->getPacketType());
  }

  /**
   * @dataProvider flagsTestDataProvider
   */
  public function testFlagsProperlyDecoded(
    int $packetType,
    array $encodedBytes
  ) {
    $receiver = $this->object->receiver();
    foreach ($encodedBytes as $byte) {
      $receiver->input(chr($byte));
    }

    $this->assertEquals($packetType, $this->object->getFlags()->get());
  }

  /**
   * @dataProvider remainingLengthTestDataProvider
   */
  public function testRemainingLengthProperlyDecoded(
    int $remainingLength,
    array $encodedBytes
  ) {
    $receiver = $this->object->receiver();
    foreach ($encodedBytes as $byte) {
      $receiver->input(chr($byte));
    }

    $this->assertEquals($remainingLength, $this->object->getRemainingLength());
  }

}
