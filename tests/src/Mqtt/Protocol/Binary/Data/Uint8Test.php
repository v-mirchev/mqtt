<?php

namespace Mqtt\Protocol\Binary\Data;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class Uint8Test extends \PHPUnit\Framework\TestCase {

  /**
   * @var Uint8
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
  }

  public function testSetLimitsTo8bitSize() {
    $this->object->set(0xFFFF);
    $this->assertEquals(0xFF, $this->object->get());
    $this->object->set(0xFF00);
    $this->assertEquals(0x00, $this->object->get());
  }

  public function testSetFromChar() {
    $this->object->set('A');
    $this->assertEquals(0x41, $this->object->get());
  }

  public function testSetBit() {
    for ($bit = 0; $bit < 8; $bit ++) {
      $this->object->set(0x00);

      $this->object->bits()->setBit($bit, true);
      $this->assertEquals(pow(2, $bit), $this->object->get());
    }

    for ($bit = 0; $bit < 8; $bit ++) {
      $this->object->set(0xFF);
      $this->object->bits()->setBit($bit, true);
      $this->assertEquals(0xFF, $this->object->get());
    }
  }

  public function testUnsetBit() {
    for ($bit = 0; $bit < 8; $bit ++) {
      $this->object->set(0x00);
      $this->object->bits()->setBit($bit, false);
      $this->assertEquals(0x00, $this->object->get());
    }
    for ($bit = 0; $bit < 8; $bit ++) {
      $this->object->set(0xFF);
      $this->object->bits()->setBit($bit, false);

      $this->assertEquals(0xFF - pow(2, $bit), $this->object->get());
    }
  }

  public function testGetBit() {
    for ($value = 0; $value < 256; $value ++) {
      $this->object->set($value);
      for ($bit = 0; $bit < 8; $bit ++) {
        $binary = str_pad(decbin($this->object->get()), 8, 0, STR_PAD_LEFT);
        $this->assertEquals(
          intval(substr($binary, - $bit - 1, 1)),
          $this->object->bits()->getBit($bit)
        );
      }
    }
  }

  public function testGetSub() {
    $this->object->set(bindec('1111 1111'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(0, 3)->get());
    $this->object->set(bindec('1111 1010'));
    $this->assertEquals(bindec('1010'), $this->object->bits()->getsub(0, 3)->get());

    $this->object->set(bindec('0000 0000'));
    $this->assertEquals(bindec('0000'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0000 0100'));
    $this->assertEquals(bindec('0001'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0000 1100'));
    $this->assertEquals(bindec('0011'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0001 1100'));
    $this->assertEquals(bindec('0111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0011 1100'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0111 1100'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('1111 1100'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('1111 1110'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('1111 1111'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('1111 1111'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0010 0000'));
    $this->assertEquals(bindec('1000'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0011 0000'));
    $this->assertEquals(bindec('1100'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0011 1000'));
    $this->assertEquals(bindec('1110'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0011 1100'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0011 1110'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
    $this->object->set(bindec('0011 1111'));
    $this->assertEquals(bindec('1111'), $this->object->bits()->getsub(2, 5)->get());
  }

  public function testSetSub() {
    $this->object->set(bindec('0000 0000'))->bits()->setSub(0, 7, bindec('111'));
    $this->assertEquals(bindec('0000 0111'), $this->object->get());
    $this->object->set(bindec('0000 0000'))->bits()->setSub(1, 7, bindec('111'));
    $this->assertEquals(bindec('0000 1110'), $this->object->get());
    $this->object->set(bindec('0000 0000'))->bits()->setSub(2, 7, bindec('111'));
    $this->assertEquals(bindec('0001 1100'), $this->object->get());
    $this->object->set(bindec('0000 0000'))->bits()->setSub(3, 7, bindec('111'));
    $this->assertEquals(bindec('0011 1000'), $this->object->get());
    $this->object->set(bindec('0000 0000'))->bits()->setSub(4, 7, bindec('111'));
    $this->assertEquals(bindec('0111 0000'), $this->object->get());
    $this->object->set(bindec('0000 0000'))->bits()->setSub(5, 7, bindec('111'));
    $this->assertEquals(bindec('1110 0000'), $this->object->get());
    $this->object->set(bindec('0000 0000'))->bits()->setSub(6, 7, bindec('111'));
    $this->assertEquals(bindec('1100 0000'), $this->object->get());
    $this->object->set(bindec('0000 0000'))->bits()->setSub(7, 7, bindec('111'));
    $this->assertEquals(bindec('1000 0000'), $this->object->get());
    $this->object->set(bindec('0000 0000'))->bits()->setSub(8, 10, bindec('111'));
    $this->assertEquals(bindec('0000 0000'), $this->object->get());

    $this->object->set(bindec('1111 0000'))->bits()->setSub(0, 7, bindec('111'));
    $this->assertEquals(bindec('0000 0111'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(1, 7, bindec('111'));
    $this->assertEquals(bindec('0000 1110'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(2, 7, bindec('111'));
    $this->assertEquals(bindec('0001 1100'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(3, 7, bindec('111'));
    $this->assertEquals(bindec('0011 1000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(4, 7, bindec('111'));
    $this->assertEquals(bindec('0111 0000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(5, 7, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(6, 7, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(7, 7, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(8, 10, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());

    $this->object->set(bindec('1111 0000'))->bits()->setSub(0, 2, bindec('111'));
    $this->assertEquals(bindec('1111 0111'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(1, 3, bindec('111'));
    $this->assertEquals(bindec('1111 1110'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(2, 4, bindec('111'));
    $this->assertEquals(bindec('1111 1100'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(3, 5, bindec('111'));
    $this->assertEquals(bindec('1111 1000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(4, 6, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(5, 7, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(6, 8, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(7, 9, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());
    $this->object->set(bindec('1111 0000'))->bits()->setSub(8, 10, bindec('111'));
    $this->assertEquals(bindec('1111 0000'), $this->object->get());

  }

  public function testStringable() {
    $this->object->set(0x41);
    $this->assertEquals('A', (string)$this->object);
  }

}
