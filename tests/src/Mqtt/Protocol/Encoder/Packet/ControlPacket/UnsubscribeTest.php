<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class UnsubscribeTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\Unsubscribe
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\Unsubscribe
   */
  protected $packet;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\Unsubscribe::class);
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Unsubscribe::class);
  }

  public function testEncodingFailsWhenIncorrectPacketType() {
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Connect::class);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->encode($this->packet);
  }

  public function testEncodesFrameProperly() {
    $this->packet->setId(0x33);
    $this->packet->topics[] = 'T#1';
    $this->packet->topics[] = 'T#2';

    $this->object->encode($this->packet);
    $this->assertEquals(\Mqtt\Protocol\IPacketType::UNSUBSCRIBE, $this->object->get()->packetType);
    $this->assertEquals(\Mqtt\Protocol\IPacketReservedBits::FLAGS_UNSUBSCRIBE, $this->object->get()->flags->get());
    $this->assertEquals(
      $this->hex2string('0033' . '0003 542331' . '0003 542332'),
      (string) $this->object->get()->payload
    );
  }

}
