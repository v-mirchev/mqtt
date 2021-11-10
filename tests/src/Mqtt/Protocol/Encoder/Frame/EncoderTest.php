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

    $expectedOutput = 'C0 00';

    $this->object->onCompleted(function (string $output) use ($expectedOutput) {
      $this->assertEquals($this->hex2string($expectedOutput), $output);
    });
    $this->object->encode($frame);
  }

  public function testEncodesProperlyFramesWithPayloadSet() {
    $frame = clone $this->frame;
    $frame->packetType = \Mqtt\Protocol\IPacketType::PUBACK;
    $frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBACK);
    $frame->payload->append(chr(03) . chr(02));

    $expectedOutput = '40 02 03 02';

    $this->object->onCompleted(function (string $output) use ($expectedOutput) {
      $this->assertEquals($this->hex2string($expectedOutput), $output);
    });
    $this->object->encode($frame);
  }

  public function testEncodesProperlyFramesWithReservedBitsSet() {
    $frame = clone $this->frame;
    $frame->packetType = \Mqtt\Protocol\IPacketType::PUBREL;
    $frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PUBREL);
    $frame->payload->append(chr(03) . chr(02));

    $expectedOutput = '62 02 03 02';

    $this->object->onCompleted(function (string $output) use ($expectedOutput) {
      $this->assertEquals($this->hex2string($expectedOutput), $output);
    });
    $this->object->encode($frame);
  }

}
