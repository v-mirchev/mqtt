<?php

namespace Mqtt\Protocol\Decoder\Frame;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class FrameTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\TestPassedAssert;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Frame
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frameEntity;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Frame\Frame::class);

    $this->frameEntity = null;
    $this->object->onCompleted(function (\Mqtt\Protocol\Entity\Frame $frame) {
      $this->frameEntity = $frame;
    });
  }

  public function testNullObjectCallbackIsSetInitially() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Frame\Frame::class);

    $receiver = $this->object->receiver();
    $bytes = [0x02, 0x04, 0x41, 0x42, 0x43, 0x44, 0xA ];
    foreach ($bytes as $byte) {
      $receiver->input(chr($byte));
    }
    $this->pass();
  }

  public function testReceiverIsNotAffectedByNullValues() {
    $receiver = $this->object->receiver();
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $this->assertFalse($receiver->isCompleted());
    $this->assertNull($this->frameEntity);
  }

  public function testReceiverIsCompletedOnStop() {
    $receiver = $this->object->receiver();
    $receiver->stop();
    $this->assertTrue($receiver->isCompleted());
    $this->assertNull($this->frameEntity);
  }

  public function testOnCompleteIsNotCalledForIncompleteByteSequence() {
    $receiver = $this->object->receiver();
    $bytes = [0x02, 0x04, 0x41, 0x42, 0x43 ];
    foreach ($bytes as $byte) {
      $receiver->input(chr($byte));
    }
    $this->assertNull($this->frameEntity);
  }

  public function testOnCompleteIsCalledForCompleteByteSequence() {
    $receiver = $this->object->receiver();
    $bytes = [0x02, 0x04, 0x41, 0x42, 0x43, 0x44, 0xA ];
    foreach ($bytes as $byte) {
      $receiver->input(chr($byte));
    }
    $this->assertNotNull($this->frameEntity);
  }

  public function packetTypeDataProvider() {
    return [
      [1,   [0x10, 0x01, 0x44 ]],
      [2,   [0x20, 0x01, 0x44 ]],
      [3,   [0x30, 0x01, 0x44 ]],
      [4,   [0x40, 0x01, 0x44 ]],
      [5,   [0x50, 0x01, 0x44 ]],
      [6,   [0x60, 0x01, 0x44 ]],
      [7,   [0x70, 0x01, 0x44 ]],
      [8,   [0x80, 0x01, 0x44 ]],
      [9,   [0x90, 0x01, 0x44 ]],
      [10,  [0xA0, 0x01, 0x44 ]],
      [11,  [0xB0, 0x01, 0x44 ]],
      [12,  [0xC0, 0x01, 0x44 ]],
      [13,  [0xD0, 0x01, 0x44 ]],
      [14,  [0xE0, 0x01, 0x44 ]],
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

    $this->assertNotNull($this->frameEntity);
    $this->assertEquals($packetType, $this->frameEntity->packetType);
  }

  public function flagsDataProvider() {
    return [
      [1,   [0x01, 0x01, 0x44 ]],
      [2,   [0x02, 0x01, 0x44 ]],
      [3,   [0x03, 0x01, 0x44 ]],
      [4,   [0x04, 0x01, 0x44 ]],
      [5,   [0x05, 0x01, 0x44 ]],
      [6,   [0x06, 0x01, 0x44 ]],
      [7,   [0x07, 0x01, 0x44 ]],
      [8,   [0x08, 0x01, 0x44 ]],
      [9,   [0x09, 0x01, 0x44 ]],
      [10,  [0x0A, 0x01, 0x44 ]],
      [11,  [0x0B, 0x01, 0x44 ]],
      [12,  [0x0C, 0x01, 0x44 ]],
      [13,  [0x0D, 0x01, 0x44 ]],
      [14,  [0x0E, 0x01, 0x44 ]],
    ];
  }

  /**
   * @dataProvider flagsDataProvider
   */
  public function testFlagsProperlyDecoded(
    int $flags,
    array $encodedBytes
  ) {
    $receiver = $this->object->receiver();
    foreach ($encodedBytes as $byte) {
      $receiver->input(chr($byte));
    }

    $this->assertNotNull($this->frameEntity);
    $this->assertEquals($flags, $this->frameEntity->flags->get());
  }

  public function payloadDataProvider() {
    return [
      ['',       [0x01, 0x00 ]],
      ['A',      [0x02, 0x01, 0x41 ]],
      ['AB',     [0x02, 0x02, 0x41, 0x42 ]],
      ['ABC',    [0x02, 0x03, 0x41, 0x42, 0x43 ]],
      ['ABCD',   [0x02, 0x04, 0x41, 0x42, 0x43, 0x44 ]],
    ];
  }

  /**
   * @dataProvider payloadDataProvider
   */
  public function testPayloadProperlyDecoded(
    string $payload,
    array $encodedBytes
  ) {
    $receiver = $this->object->receiver();
    foreach ($encodedBytes as $byte) {
      $receiver->input(chr($byte));
    }

    $this->assertNotNull($this->frameEntity);
    $this->assertEquals($payload, $this->frameEntity->payload->getString());
  }

}
