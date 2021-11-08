<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class DecoderTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Decoder
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Decoder::class);
  }

  public function testDecodeFailsForUnknownPacketType() {
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);

    $bytes = [0x02, 0x02, 0x00, 0x01, 0x02, 0x00 ];
    foreach ($bytes as $byte) {
      $this->object->decode(chr($byte));
    }
  }

  public function testDecodesProperlyCharInputToPacket() {
    $this->object->onDecodingCompleted(function (\Mqtt\Protocol\Entity\Packet\IPacket $packet) {
      $this->assertInstanceOf(\Mqtt\Protocol\Entity\Packet\PingResp::class, $packet);
    });

    $bytes = [0xD0, 0x00];
    foreach ($bytes as $byte) {
      $this->object->decode(chr($byte));
    }
  }

}
