<?php

namespace Mqtt\Entity\Configuration;

class ConfigurationTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Configuration
   */
  protected $object;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $serverMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $sessionMock;

  protected function setUp() {
    $this->serverMock = $this->getMockBuilder(\Mqtt\Entity\Configuration\Server::class)->
      disableOriginalConstructor()->
      getMock();

    $this->sessionMock = $this->getMockBuilder(\Mqtt\Entity\Configuration\Session::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Configuration(
      $this->serverMock,
      $this->sessionMock
    );
  }

  public function testUseAuthenticationFluentlyReturnsAuthentication() {
    $this->assertSame($this->serverMock, $this->object->server());
  }

  public function testUseWillFluentlyReturnsWill() {
    $this->assertSame($this->sessionMock, $this->object->session());
  }

}
