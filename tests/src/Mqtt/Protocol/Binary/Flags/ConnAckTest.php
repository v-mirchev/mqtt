<?php

namespace Mqtt\Protocol\Binary\Flags;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class ConnAckTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var ConnAck
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(ConnAck::class);
  }

  public function testCloneResetsInstance() {
    $cloning = clone $this->object;

    $this->assertFalse($cloning->getSessionPresent());
    $this->assertEmpty($cloning->getReturnCode());
  }

  public function testSessionPresentProperlyWritenAndRead() {
    $this->object->set(0x0000);
    $this->assertFalse($this->object->getSessionPresent());

    $this->object->set(0x0100);
    $this->assertTrue($this->object->getSessionPresent());
  }

  public function testReturnCodeProperlyWritenAndRead() {
    for ($returnCode = 0; $returnCode < 5; $returnCode ++) {
      $this->object->set($returnCode);
      $this->assertEquals($returnCode, $this->object->getReturnCode());
    }
  }

}
