<?php

namespace Mqtt\Protocol\Binary\Flags;

class ConnAckTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var ConnAck
   */
  protected $object;

  protected function setUp() {
    $this->object = new ConnAck;
  }

  public function testCloneResetsInstance() {
    $cloning = clone $this->object;

    $this->assertFalse($cloning->getSessionPresent());
    $this->assertNull($cloning->getReturnCode());
  }

  public function testSetFailsOnEmptyInput() {
    $this->expectException(\Exception::class);
    $this->object->set('');
  }

  public function testSetFailsOnTooShortInput() {
    $this->expectException(\Exception::class);
    $this->object->set('A');
  }

  public function testSetFailsOnTooLongInput() {
    $this->expectException(\Exception::class);
    $this->object->set('AAA');
  }

  public function testSessionPresentProperlyWritenAndRead() {
    $this->object->set($this->toStringStream(0x00, 0x00));
    $this->assertFalse($this->object->getSessionPresent());

    $this->object->set($this->toStringStream(0x01, 0x00));
    $this->assertTrue($this->object->getSessionPresent());
  }

  public function testReturnCodeProperlyWritenAndRead() {
    for ($returnCode = 0; $returnCode < 5; $returnCode ++) {
      $this->object->set($this->toStringStream(0x00, $returnCode));
      $this->assertEquals($returnCode, $this->object->getReturnCode());
    }
  }

}
