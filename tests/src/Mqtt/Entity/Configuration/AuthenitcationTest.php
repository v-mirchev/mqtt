<?php

namespace Mqtt\Entity\Configuration;

class AuthenticationTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Authentication
   */
  protected $object;

  protected function setUp() {
    $this->object = new Authentication();
  }

  public function testSetUsernameSetsProperty() {
    $username = '#username';
    $this->object->username($username);
    $this->assertEquals($username, $this->object->username);
  }

  public function testSetPasswordSetsProperty() {
    $password = '#password';
    $this->object->password($password);
    $this->assertEquals($password, $this->object->password);
  }

  public function testSetUsernameFluentlySetsUsername() {
    $this->assertSame($this->object, $this->object->username(''));
  }

  public function testSetPasswordFluentlySetsPassword() {
    $this->assertSame($this->object, $this->object->password(''));
  }


}
