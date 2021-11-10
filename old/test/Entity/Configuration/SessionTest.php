<?php declare(strict_types = 1);

namespace Mqtt\Entity\Configuration;

class SessionTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Session
   */
  protected $object;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $authenticationMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $willMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $protocolMock;

  protected function setUp() {
    $this->authenticationMock = $this->getMockBuilder(\Mqtt\Entity\Configuration\Authentication::class)->
      disableOriginalConstructor()->
      getMock();

    $this->willMock = $this->getMockBuilder(\Mqtt\Entity\Configuration\Will::class)->
      disableOriginalConstructor()->
      getMock();

    $this->protocolMock = $this->getMockBuilder(\Mqtt\Entity\Configuration\Protocol::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Session(
      $this->authenticationMock,
      $this->willMock,
      $this->protocolMock
    );
  }

  public function testUseAuthenticationSetsPropery() {
    $useAuthentication = true;
    $this->object->authentication($useAuthentication);
    $this->assertEquals($useAuthentication, $this->object->useAuthentication);
  }

  public function testUseWillSetsPropery() {
    $useWill = true;
    $this->object->will($useWill);
    $this->assertEquals($useWill, $this->object->useWill);
  }

  public function testSetClientIdSetsProperty() {
    $clientid = '#clientid';
    $this->object->clientId($clientid);
    $this->assertEquals($clientid, $this->object->clientId);
  }

  public function testSetKeepAliveIntervalSetsProperty() {
    $keepAliveInterval = 37;
    $this->object->keepaliveinterval($keepAliveInterval);
    $this->assertEquals($keepAliveInterval, $this->object->keepAliveInterval);
  }

  public function testSetPersistentSetsProperty() {
    $isPersistent = true;
    $this->object->usePersistent($isPersistent);
    $this->assertEquals($isPersistent, $this->object->isPersistent);
  }

  public function testUseAuthenticationFluentlyReturnsAuthentication() {
    $this->assertSame($this->authenticationMock, $this->object->authentication());
  }

  public function testUseWillFluentlyReturnsWill() {
    $this->assertSame($this->willMock, $this->object->will());
  }

  public function testProtocolFluentlyReturnsProtocol() {
    $this->assertSame($this->protocolMock, $this->object->protocol());
  }

  public function testSetClientIdFluentlySetsisPersistent() {
    $this->assertSame($this->object, $this->object->clientId(''));
  }

  public function testSetKeepAliveIntervalFluentlySetsPassword() {
    $this->assertSame($this->object, $this->object->keepaliveinterval(37));
  }

  public function testSetPersistentFluentlySetsIsPersistent() {
    $this->assertSame($this->object, $this->object->usePersistent());
  }

}
