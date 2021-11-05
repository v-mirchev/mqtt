<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PublishTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\ControlPacket\Publish
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Packet\ControlPacket\Publish::class);

    $this->frame = clone $this->___container->get(\Mqtt\Protocol\Entity\Frame::class);
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PUBLISH;
    $this->frame->flags = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
    $this->frame->payload->append(chr(0x0) . chr(0x5) . 'TOPIC');
    $this->frame->payload->append('MESSAGE');
  }

  public function testInitialCleanState() {
    $object = clone $this->object;
    $this->assertEquals(\Mqtt\Protocol\IPacketType::PUBLISH, $object->get()->getType());
  }

  public function testDecodeFailsWhenIncorrectPacketTypeSetInFrame() {
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::CONNECT;
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->decode($this->frame);
  }

  public function flagsDataProvider() {
    return [
      [0b0000, false, false, 0 ],
      [0b0001, true,  false, 0 ],
      [0b0010, false, false, 1 ],
      [0b0011, true,  false, 1 ],

      [0b0100, false, false, 2 ],
      [0b0101, true,  false, 2 ],

      [0b1000, false, true,  0 ],
      [0b1001, true,  true,  0 ],
      [0b1010, false, true,  1 ],
      [0b1011, true,  true,  1 ],

      [0b1100, false, true,  2 ],
      [0b1101, true,  true,  2 ],
    ];
  }

  /**
   * @dataProvider flagsDataProvider
   */
  public function testProperlyDecodingRetainDupQos(
    int $encodedFlags, bool $retain, bool $dup, int $qos
  ) {
    $this->frame->flags->bits()->set($encodedFlags);
    $this->frame->payload->append(chr(0x0) . chr(0x5) . 'TOPIC');
    $this->frame->payload->append('MESSAGE');

    $this->object->decode($this->frame);

    $this->assertEquals($qos, $this->object->get()->qosLevel);
    $this->assertEquals($retain, $this->object->get()->isRetain);
    $this->assertEquals($dup, $this->object->get()->isDuplicate);
  }

  public function testProperlyDecodingIdentificatorForQos1() {
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\IBuffer::class);
    $this->frame->flags->bits()->set(0b00000010);
    $this->frame->payload->append(chr(0x00) . chr(0x05) . 'TOPIC');
    $this->frame->payload->append(chr(0x01) . chr(0x02));
    $this->frame->payload->append('MESSAGE');

    $this->object->decode($this->frame);

    $this->assertEquals(0x0102, $this->object->get()->getId());
  }

  public function testProperlyDecodingReturnsPublishPacketEntity() {
    $this->object->decode($this->frame);
    $this->assertInstanceOf(\Mqtt\Protocol\Entity\Packet\Publish::class, $this->object->get());
  }

}
