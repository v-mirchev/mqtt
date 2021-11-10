<?php declare(strict_types = 1);

namespace Mqtt\Protocol;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class CodecTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\TestPassedAssert;
  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Codec
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Codec::class);
  }

  public function testCloneIsCleanInstanceForEncoding() {
    $this->object->onEncodingCompleted(function () {
      $this->fail('Cloning must not call original callback');
    });

    $object = clone $this->object;
    $object->encode(new \Mqtt\Protocol\Entity\Packet\Disconnect());
    $this->pass();
  }

  public function testCloneIsCleanInstanceForDecoding() {
    $this->object->onDecodingCompleted(function () {
      $this->fail('Cloning must not call original callback');
    });

    $object = clone $this->object;
    $object->decode($this->hex2string('D0 00'));
    $this->pass();
  }

  public function testEncodingCallBackCalledProperly() {
    $this->object->onEncodingCompleted(function (string $data) {
      $this->assertEquals($this->hex2string('E0 00'), $data);
    });

    $this->object->encode(new \Mqtt\Protocol\Entity\Packet\Disconnect());
  }

  public function testDecodingCallBackCalledProperly() {
    $this->object->onDecodingCompleted(function (\Mqtt\Protocol\Entity\Packet\IPacket $packet) {
      $this->assertInstanceOf(\Mqtt\Protocol\Entity\Packet\PingResp::class, $packet);
    });

    $this->object->decode($this->hex2string('D0 00'));
  }

}
