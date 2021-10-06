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

  public function testContentEncodingDecoding() {
    $content = 'ABC567';
    $object = $this->object->create($content);
    $object->set((string)$object);

    $this->assertEquals($content, $object->get());
    $this->assertEquals($this->toStringStream(0x00, 0x06, 0x41, 0x42, 0x43, 0x35, 0x36, 0x37), (string)$object);
  }

  public function testByteEncodingDecoding() {
    $byte = 0x05;
    $object = $this->object->createByte($byte);
    $object->set((string)$object);

    $this->assertEquals($byte, $object->getByte());
    $this->assertEquals($this->toStringStream(0x05), (string)$object);
  }

  public function testIdentifierEncodingDecoding() {
    $identifier = 0xA5;
    $object = $this->object->createIdentifier($identifier);
    $object->set((string)$object);

    $this->assertEquals($identifier, $object->getIdentifier());
    $this->assertEquals($this->toStringStream(0x00, 0xa5), (string)$object);
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
