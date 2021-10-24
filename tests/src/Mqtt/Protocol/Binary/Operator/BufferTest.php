<?php

namespace Mqtt\Protocol\Binary\Operator;

class BufferTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Buffer
   */
  protected $object;

  protected function setUp() {
    $this->object = new Buffer();
  }

  public function testInitiallyEmpty() {
    $this->assertEmpty($this->object->getString());
  }

  public function testCloneResets() {
    $this->object->set('ABC');
    $object = clone $this->object;
    $this->assertEmpty($object->getString());
  }

  public function testInitiallyEoF() {
    $this->assertTrue($this->object->eof());
  }

  public function testEoFAtfterBufferSetNotTrue() {
    $this->object->set('ABC');
    $this->assertFalse($this->object->eof());
  }

  public function testEoFAtfterBufferConsumedIsTrue() {
    $this->object->set('ABC');
    $this->object->getString();
    $this->assertTrue($this->object->eof());
  }

  public function testThrowsExceptionWhenReadingStringOutsideBuffer() {
    $this->object->set('ABC');
    $this->expectException(\Exception::class);
    $this->object->getString(4);
  }

  public function testThrowsExceptionWhenReadingBytesOutsideBuffer() {
    $this->object->set('ABC');
    $this->expectException(\Exception::class);
    $this->object->getBytes(4);
  }

  public function testThrowsExceptionWhenReadingByteOutsideBuffer() {
    $this->object->set('ABC');
    $this->object->getBytes(3);

    $this->expectException(\Exception::class);
    $this->object->getByte();
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

  public function testReadBytesWithSpecfiedCountPropely() {
    $this->object->set('ABCD');
    $this->assertEquals([ 0x41, 0x42], $this->object->getBytes(2));
  }

  public function testReadBytesShiftsBuffer() {
    $this->object->set('ABCDE');
    $this->object->getBytes(2);
    $this->assertEquals([ 0x43, 0x44], $this->object->getBytes(2));
  }

  public function testReadAsBytesReturnsRemainingBufferBytes() {
    $this->object->set('ABCDE');
    $this->object->getBytes(2);
    $this->assertEquals([ 0x43, 0x44, 0x45], $this->object->getBytes());
  }

  public function testReadAsBytesEmptiesBufferBytes() {
    $this->object->set('ABCDE');
    $this->object->getBytes();
    $this->assertEmpty($this->object->getBytes());
  }

  public function testReadByteReturnsProperlySingleByteFromBuffer() {
    $this->object->set('ABCDE');
    $this->assertEquals(0x41, $this->object->getByte());
  }

  public function testReadByteShiftsBuffer() {
    $this->object->set('ABCDE');
    $this->object->getByte();
    $this->assertEquals(0x42, $this->object->getByte());
  }

}
