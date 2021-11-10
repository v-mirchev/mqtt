<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PublishTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\Publish
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\Publish
   */
  protected $packet;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\Publish::class);
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Publish::class);
  }

  public function testEncodingFailsWhenIncorrectPacketType() {
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Connect::class);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->encode($this->packet);
  }

  public function testEncodesPacketTypeProperly() {
    $this->packet->setId(0x33);
    $this->packet->topic = 'TOPIC';
    $this->packet->message = 'CONTENT';

    $this->object->encode($this->packet);
    $this->assertEquals(\Mqtt\Protocol\IPacketType::PUBLISH, $this->object->get()->packetType);
  }

  public function testEncodesFlagsProperly() {
    $this->packet->setId(0x33);
    $this->packet->topic = 'TOPIC';
    $this->packet->message = 'CONTENT';
    $this->packet->isDuplicate = true;
    $this->packet->isRetain = true;
    $this->packet->qosLevel = 2;

    $this->object->encode($this->packet);
    $this->assertEquals(0x0D, $this->object->get()->flags->get());
  }

  public function testEncodesFramePayloadProperly() {
    $this->packet->setId(0x33);
    $this->packet->topic = 'TOPIC';
    $this->packet->message = 'CONTENT';
    $this->object->encode($this->packet);

    $this->assertEquals(
      $this->hex2string('0005544f504943434f4e54454e54'),
      (string) $this->object->get()->payload
    );
  }

}
