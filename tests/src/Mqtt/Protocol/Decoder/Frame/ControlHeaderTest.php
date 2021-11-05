<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class ControlHeaderTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\ControlHeader
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Frame\ControlHeader::class);
  }

  public function testClonedObjectIsInCleanState() {
    $object = clone $this->object;
    $this->assertEquals(0x0, $object->getFlags()->get());
    $this->assertEquals(0x0, $object->getPacketType());
  }

  public function testReceiverIsNotAffectedByNullValues() {
    $receiver = $this->object->receiver();
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $this->assertFalse($receiver->isCompleted());
  }

  public function testReceiverIsCompletedOnSingleChar() {
    $receiver = $this->object->receiver();
    $receiver->input(chr(0x80));
    $this->assertTrue($receiver->isCompleted());
  }

  public function testReceiverParsesFlagsCorrectly() {
    $receiver = $this->object->receiver();

    $receiver->input(chr(0x84));
    $this->assertEquals(0x04, $this->object->getFlags()->get());
  }

  public function testReceiverParsesPacketTypeCorrectly() {
    $receiver = $this->object->receiver();

    $receiver->input(chr(0x84));
    $this->assertEquals(0x08, $this->object->getPacketType());
  }

}
