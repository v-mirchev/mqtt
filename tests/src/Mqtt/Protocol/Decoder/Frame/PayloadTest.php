<?php

namespace Mqtt\Protocol\Decoder\Frame;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PayloadTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Payload
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Frame\Payload::class);
  }

  public function testClonedObjectIsInCleanState() {
    $object = clone $this->object;
    $this->assertEquals('', $object->get()->getString());
  }

  public function testReceiverIsNotAffectedByNullValues() {
    $this->object->setLength(1);
    $receiver = $this->object->receiver();
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $receiver->input(null);
    $this->assertFalse($receiver->isCompleted());
  }

  public function testDecodesProperlyByLength() {
    $this->object->setLength(3);
    $receiver = $this->object->receiver();
    foreach (['A', 'B' , 'C', '#', 'D', 'E', 'F' ] as $char) {
      $receiver->input($char);
    }

    $this->assertEquals('ABC', $this->object->get()->getString());
  }

  public function testProperlySetsAsCompleted() {
    $this->object->setLength(3);
    $receiver = $this->object->receiver();
    foreach (['A', 'B' , 'C', '#' ] as $char) {
      $receiver->input($char);
    }

    $this->assertTrue($receiver->isCompleted());
  }

  public function testMustBeCompletedForZeroLength() {
    $this->object->setLength(0);
    $receiver = $this->object->receiver();
    $this->assertTrue($receiver->isCompleted());
  }

}
