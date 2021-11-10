<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class EncoderTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\TestPassedAssert;
  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Encoder
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Encoder::class);
  }

  public function testCloneIsCleanInstance() {
    $this->object->onEncodingCompleted(function () {
      $this->fail('Cloning must not call original callback');
    });

    $object = clone $this->object;
    $object->encode(new \Mqtt\Protocol\Entity\Packet\Disconnect());
    $this->pass();
  }

  public function testCallBackCalledProperly() {
    $this->object->onEncodingCompleted(function (string $data) {
      $this->assertEquals($this->hex2string('E000'), $data);
    });

    $this->object->encode(new \Mqtt\Protocol\Entity\Packet\Disconnect());
  }

}
