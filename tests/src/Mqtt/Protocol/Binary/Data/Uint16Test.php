<?php

namespace Mqtt\Protocol\Binary\Data;

class Wint16Test extends \PHPUnit\Framework\TestCase {

  /**
   * @var Uint16
   */
  protected $object;

  protected function setUp() {
    $this->object = new Uint16(new Uint8());
  }

  public function testSetLimitsTo16bitSize() {
    $this->object->set(0xFFFFFFFF);
    $this->assertEquals(0xFFFF, $this->object->get());
    $this->object->set(0xFF0000);
    $this->assertEquals(0x0000, $this->object->get());
  }

  public function testSetFromChar() {
    $this->object->setBytes('A', 'B');
    $this->assertEquals(0x4142, $this->object->get());
  }

  public function testStringable() {
    $this->object->set(0x4142);
    $this->assertEquals('AB', (string)$this->object);
  }

}
