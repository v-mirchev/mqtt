<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class ConnAckTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags\ConnAck
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  protected $buffer;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags\ConnAck::class);
    $this->buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
  }

  public function testInitialStateClean() {
    $this->assertFalse($this->object->getSessionPresent());
  }

  public function testClonedStateClean() {
    $object = clone $this->object;
    $this->assertFalse($object->getSessionPresent());
  }

  public function testDecodeFailsWrongReservedBits() {
    $this->buffer->append(chr(0xFF));
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PAYLOAD_RESERVED_BITS);
    $this->object->decode($this->buffer);
  }

  public function testDecodeSetsCleanSessionProperly() {
    $this->buffer->append(chr(0x00));
    $this->object->decode($this->buffer);
    $this->assertFalse($this->object->getSessionPresent());

    $this->buffer->append(chr(0x01));
    $this->object->decode($this->buffer);
    $this->assertTrue($this->object->getSessionPresent());
  }

}
