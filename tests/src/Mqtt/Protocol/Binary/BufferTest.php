<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Binary;

class BufferTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Binary\Buffer
   */
  protected $object;

  protected function setUp() {
    $this->object = new \Mqtt\Protocol\Binary\Buffer();
  }

  public function testInitiallyEmpty() {
    $this->assertEmpty($this->object->getString());
    $this->assertTrue($this->object->isEmpty());
  }

  public function testCloneResets() {
    $this->object->set('ABC');
    $object = clone $this->object;
    $this->assertEmpty($object->getString());
  }

  public function testEoFAtfterBufferSetNotTrue() {
    $this->object->set('ABC');
    $this->assertFalse($this->object->isEmpty());
  }

  public function testEoFAtfterBufferConsumedIsTrue() {
    $this->object->set('ABC');
    $this->object->getString();
    $this->assertTrue($this->object->isEmpty());
  }

  public function testThrowsExceptionWhenReadingStringOutsideBuffer() {
    $this->object->set('ABC');
    $this->expectException(\Exception::class);
    $this->object->getString(4);
  }

  public function testReadStringWithSpecifiedLengthProperly() {
    $this->object->set('ABC');
    $this->assertEquals('AB', $this->object->getString(2));
  }

  public function testReadingStringShiftsBuffer() {
    $this->object->set('ABCDE');
    $this->object->getString(2);
    $this->assertEquals('CD', $this->object->getString(2));
  }

  public function testReadingStringWithoutLengthReturnsRemainingBuffer() {
    $this->object->set('ABCDE');
    $this->object->getString(2);
    $this->assertEquals('CDE', $this->object->getString());
  }

  public function testAsStringRturnsRemainingBuffer() {
    $this->object->set('ABCD');
    $this->object->getString(2);
    $this->assertEquals('CD', (string)$this->object);
  }

  public function testAsStringRturnsEmptiesBuffer() {
    $this->object->set('ABCD');
    (string)$this->object;
    $this->assertEmpty($this->object->getString());
  }

}
