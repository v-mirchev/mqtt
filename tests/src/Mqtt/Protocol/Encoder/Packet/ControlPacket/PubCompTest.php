<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PubCompTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\PubComp
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\IPacket
   */
  protected $packet;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\PubComp::class);
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\PubComp::class);
  }

  public function testEncodingFailsWhenIncorrectPacketType() {
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Connect::class);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->encode($this->packet);
  }

  public function testEncodesFrameProperly() {
    $this->packet->setId(0x33);
    $this->object->encode($this->packet);
    $this->assertEquals(\Mqtt\Protocol\IPacketType::PUBCOMP, $this->object->get()->packetType);
    $this->assertEquals(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBCOMP, $this->object->get()->flags->get());
    $this->assertEquals($this->hex2string('0033'), (string) $this->object->get()->payload);
  }

}
