<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Frame;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class EncoderTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Frame\Encoder
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Frame\Encoder::class);
    $this->frame = clone $this->___container->get(\Mqtt\Protocol\Entity\Frame::class);
  }

  public function testEncodesProperlyFramesWithoutPayloadSet() {
    $frame = clone $this->frame;
    $frame->packetType = \Mqtt\Protocol\IPacketType::PINGREQ;
    $frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PINGREQ);

    $expectedOutput = [0xC0, 0x00];

    $this->object->onCompleted(function (string $output) use ($expectedOutput) {
      $this->assertEquals($this->toStringStream($expectedOutput), $output);
    });
    $this->object->encode($frame);
  }

  public function testEncodesProperlyFramesWithPayloadSet() {
    $frame = clone $this->frame;
    $frame->packetType = \Mqtt\Protocol\IPacketType::PUBACK;
    $frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBACK);
    $frame->payload->append(chr(0x03) . chr(0x02));

    $expectedOutput = [0x40, 0x02, 0x03, 0x02];

    $this->object->onCompleted(function (string $output) use ($expectedOutput) {
      $this->assertEquals($this->toStringStream($expectedOutput), $output);
    });
    $this->object->encode($frame);
  }

  public function testEncodesProperlyFramesWithReservedBitsSet() {
    $frame = clone $this->frame;
    $frame->packetType = \Mqtt\Protocol\IPacketType::PUBREL;
    $frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREL);
    $frame->payload->append(chr(0x03) . chr(0x02));

    $expectedOutput = [0x62, 0x02, 0x03, 0x02];

    $this->object->onCompleted(function (string $output) use ($expectedOutput) {
      $this->assertEquals($this->toStringStream($expectedOutput), $output);
    });
    $this->object->encode($frame);
  }

}
