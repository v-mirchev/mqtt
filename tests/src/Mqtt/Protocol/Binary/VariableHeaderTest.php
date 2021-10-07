<?php

namespace Mqtt\Protocol\Binary;

class VariableHeaderTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var VariableHeader
   */
  protected $object;

  protected function setUp() {
    $this->object = new VariableHeader;
  }

  public function testCloneResetsInstance() {
    $object = clone $this->object;
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$object);
    $this->assertEmpty($object->getBody());
  }

  public function testInitialStateEmpty() {
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string) $this->object);
    $this->assertEmpty($this->object->getBody());
  }


  public function testContentEncoding() {
    $content = str_repeat('ABCD' , 129);
    $object = $this->object->create($content);
    $this->assertEquals(
      $this->stringToStringStream('0204' . str_repeat('41424344', 129)),
      (string) $object
    );
  }

  public function testContentDecoding() {
    $content = str_repeat('ABCD' , 129);
    $this->object->set($this->stringToStringStream('0204' . str_repeat('41424344', 129)) . $this->randomStringStream());
    $this->assertEquals($content, $this->object->get());
  }

  public function testByteEncoding() {
    $byte = 0x05;
    $object = $this->object->createByte($byte);
    $this->assertEquals($this->toStringStream($byte), (string)$object);
  }

  public function testByteDecoding() {
    $byte = 0x05;
    $this->object->set($this->toStringStream(0x05) . $this->randomStringStream());
    $this->assertEquals($byte, $this->object->getByte());
  }

  public function testIdentifierEncoding() {
    $identifier = 0xA5;
    $object = $this->object->createIdentifier($identifier);
    $this->assertEquals($this->toStringStream(0x00, 0xa5), (string)$object);
  }

  public function testIdentifierDecoding() {
    $identifier = 0xA5;
    $this->object->set($this->toStringStream(0x00, 0xa5) . $this->randomStringStream());
    $this->assertEquals($identifier, $this->object->getIdentifier());
  }

  public function testSetGetBody() {
    $this->object->set('ABCDEF');
    $this->assertEquals('ABCDEF', $this->object->getBody());
  }

  public function testGetIdentifiersProcessesProperlyEmptyBody() {
    $this->assertEquals([], $this->object->getBytes());
  }

  public function testGetIdentifiersProcessesProperlyBody() {
    $bytes = [ 0x1, 0x5, 0x9 ];
    $this->object->set(call_user_func_array([ $this, 'toStringStream' ], $bytes));
    $this->assertEquals($bytes, $this->object->getBytes());
  }

}
