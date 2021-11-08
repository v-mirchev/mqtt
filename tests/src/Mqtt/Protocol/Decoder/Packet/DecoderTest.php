<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class DecoderTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\TestPassedAssert;

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\Decoder
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Packet\Decoder::class);
    $this->frame = clone $this->___container->get(\Mqtt\Protocol\Entity\Frame::class);
  }

  public function testDecodeFailsForUnknownPacketType() {
    $this->frame->packetType = -1;

    $this->expectException(\Exception::class);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsForUnknownPaloaData() {
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PINGRESP;
    $this->frame->payload->set('ABC');

    $this->expectException(\Exception::class);
    $this->object->decode($this->frame);
  }

  public function testDecodeCallsCallbackOnSuccess() {
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PINGRESP;

    $this->object->onCompleted(function () {
      $this->pass();
    });

    $this->object->decode($this->frame);
  }

  public function testDecodeCallsCallbackWithProperPacketOnSuccess() {
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PINGRESP;

    $this->object->onCompleted(function (\Mqtt\Protocol\Entity\Packet\IPacket $packet) {
      $this->assertInstanceOf(\Mqtt\Protocol\Entity\Packet\PingResp::class, $packet);
    });

    $this->object->decode($this->frame);
  }

  public function testDecodePeovidesProperPacketForGetOnSuccess() {
    $this->frame->packetType = \Mqtt\Protocol\IPacketType::PINGRESP;
    $this->object->decode($this->frame);
    $packet = $this->object->get();
    $this->assertInstanceOf(\Mqtt\Protocol\Entity\Packet\PingResp::class, $packet);
  }

}
