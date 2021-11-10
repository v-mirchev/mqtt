<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class ConnectTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\Connect
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\Connect
   */
  protected $packet;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\Connect::class);
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Connect::class);
    $this->packet->cleanSession = true;
  }

  public function testEncodingFailsWhenIncorrectPacketType() {
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Disconnect::class);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->encode($this->packet);
  }

  public function testEncodesProperlyPacketType() {
    $this->object->encode($this->packet);
    $this->assertEquals(\Mqtt\Protocol\IPacketType::CONNECT, $this->object->get()->packetType);
  }

  public function testEncodesProperlyPacketFalgs() {
    $this->object->encode($this->packet);
    $this->assertEquals(\Mqtt\Protocol\IPacketReservedBits::FLAGS_CONNECT, $this->object->get()->flags->get());
  }

  public function testEncodesProperlyNoFlagsSet() {
    $this->object->encode($this->packet);
    $this->assertEquals(\Mqtt\Protocol\IPacketType::CONNECT, $this->object->get()->packetType);
    $this->assertEquals(\Mqtt\Protocol\IPacketReservedBits::FLAGS_CONNECT, $this->object->get()->flags->get());
    $this->assertEquals(
      $this->hex2string('0004 4d515454 04 02 00000000'),
      (string) $this->object->get()->payload
    );
  }

  public function testEncodingFailsIncorrectClientIdUsage() {
    $this->packet->cleanSession = false;
    $this->packet->clientId = '';

    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_CLIENTID_SETUP);
    $this->object->encode($this->packet);
  }

  public function testEncodingFailsIncorrectUsernameUsage() {
    $this->packet->useUsername = true;
    $this->packet->username = '';

    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_AUTHENTICATION_SETUP);
    $this->object->encode($this->packet);
  }

  public function testEncodingFailsIncorrectAuthenticationUsage() {
    $this->packet->useUsername = false;
    $this->packet->usePassword = true;

    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_AUTHENTICATION_SETUP);
    $this->object->encode($this->packet);
  }

  public function testEncodingFailsIncorrectWillUsage() {
    $this->packet->useWill = true;

    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_WILL_SETUP);
    $this->object->encode($this->packet);
  }

  public function testEncodingFailsIncorrectWillMessageWhenNoWillUsage() {
    $this->packet->useWill = false;
    $this->packet->willMessage = 'MESSAGE';

    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_WILL_SETUP);
    $this->object->encode($this->packet);
  }

  public function testEncodingFailsIncorrectWillTopicWhenNoWillUsage() {
    $this->packet->useWill = false;
    $this->packet->willTopic = 'TOPIC';

    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONNECT_WILL_SETUP);
    $this->object->encode($this->packet);
  }

  public function testEncodesProperlyKeepAlive() {
    $this->packet->keepAlive = 128;

    $this->object->encode($this->packet);
    $this->assertEquals(
      $this->hex2string('0004 4d515454 04 02 0080 0000'),
      (string) $this->object->get()->payload
    );
  }

  public function testEncodesProperlyCleanSessionOnly() {
    $this->packet->cleanSession = true;

    $this->object->encode($this->packet);
    $this->assertEquals(
      $this->hex2string('0004 4d515454 04 02 0000 0000'),
      (string) $this->object->get()->payload
    );
  }

  public function testEncodesProperlyClientIdOnly() {
    $this->packet->cleanSession = false;
    $this->packet->clientId = '#CLIENTID';

    $this->object->encode($this->packet);
    $this->assertEquals(
      $this->hex2string('0004 4d515454 04 00 0000 0009 23434c49454e544944'),
      (string) $this->object->get()->payload
    );
  }

  public function testEncodesProperlyUsernameOnly() {
    $this->packet->useUsername = true;
    $this->packet->username = '#USERNAME';

    $this->object->encode($this->packet);
    $this->assertEquals(
      $this->hex2string('0004 4d515454 04 82 0000 0000 0009 23555345524E414D45'),
      (string) $this->object->get()->payload
    );
  }

  public function testEncodesProperlyUsernameAndPassword() {
    $this->packet->useUsername = true;
    $this->packet->username = '#USERNAME';
    $this->packet->usePassword = true;
    $this->packet->password = '#PASSWORD';

    $this->object->encode($this->packet);
    $this->assertEquals(
      $this->hex2string('0004 4d515454 04 C2 0000 0000 0009 23555345524E414D45 0009 2350415353574F5244'),
      (string) $this->object->get()->payload
    );
  }

  public function testEncodesProperlyWill() {
    $this->packet->useWill = true;
    $this->packet->willMessage = '#MESSAGE';
    $this->packet->willTopic = '#TOPIC';
    $this->packet->willQos = 0x02;

    $this->object->encode($this->packet);
    $this->assertEquals(
      $this->hex2string('0004 4d515454 04 16 0000 0000 0006 23544f504943 0008 234d455353414745'),
      (string) $this->object->get()->payload
    );
  }

}
