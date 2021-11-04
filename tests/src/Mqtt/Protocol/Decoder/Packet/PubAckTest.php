<?php

namespace Mqtt\Protocol\Decoder\Packet;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PubAckTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\PubAck
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Packet\PubAck::class);

    $this->frame = clone $this->___container->get(\Mqtt\Protocol\Entity\Frame::class);
    $this->frame->packetType = \Mqtt\Protocol\Packet\IType::PUBACK;
    $this->frame->flags = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBACK);
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
    $this->frame->payload->append(chr(0x0) . chr(0x0));
  }

  public function testInitialCleanState() {
    $object = clone $this->object;
    $this->assertEquals(\Mqtt\Protocol\Packet\IType::PUBACK, $object->get()->getType());
  }

  public function testDecodeFailsWhenIncorrectPacketTypeSetInFrame() {
    $this->frame->packetType = \Mqtt\Protocol\Packet\IType::CONNECT;
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsWhenIncorrectValuesForReservedPacketFlags() {
    $this->frame->flags->set(0xFF);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONTROL_HEADER_RESERVED_BITS);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsWhenPaylodNotFullyDecoded() {
    $this->frame->payload->append(chr(0x0) . chr(0x0) . chr(0x5));
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::UNKNOWN_PAYLOAD_DATA);
    $this->object->decode($this->frame);
  }

  public function testProperlyDecodingIdentificator() {
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
    $this->frame->payload->append(chr(0x01) . chr(0x02));
    $this->object->decode($this->frame);
    $this->assertEquals(0x0102, $this->object->get()->getId());
  }

  public function testProperlyDecodingReturnsPubAckPacketEntity() {
    $this->object->decode($this->frame);
    $this->assertInstanceOf(\Mqtt\Protocol\Entity\Packet\PubAck::class, $this->object->get());
  }

}
