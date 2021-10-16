<?php

namespace Mqtt\Entity\Configuration;

class ServerTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Server
   */
  protected $object;

  protected function setUp() {
    $this->object = new Server();
  }

  public function testSetHostSetsProperty() {
    $host = '#host';
    $this->object->host($host);
    $this->assertEquals($host, $this->object->host);
  }

  public function testSetPortSetsProperty() {
    $port = 1234;
    $this->object->port($port);
    $this->assertEquals($port, $this->object->port);
  }

  public function testSetCertificateSetsProperty() {
    $certificate = '#certificate';
    $this->object->certificate($certificate);
    $this->assertEquals($certificate, $this->object->certificateFile);
  }

  public function testSetTimeoutSetsProperty() {
    $timeout = 1000;
    $this->object->timeout($timeout);
    $this->assertEquals($timeout, $this->object->timeout);
  }

  public function testSetConnectTimeoutSetsProperty() {
    $connectTimeout = 2000;
    $this->object->connectTimeout($connectTimeout);
    $this->assertEquals($connectTimeout, $this->object->connectTimeout);
  }

  public function testSetSelectTimeoutSetsProperty() {
    $selectTimeout = 2000;
    $this->object->selectTimeout($selectTimeout);
    $this->assertEquals($selectTimeout, $this->object->selectTimeout);
  }

  public function testSetHostFluentlySetsHost() {
    $this->assertSame($this->object, $this->object->host(''));
  }

  public function testSetPortFluentlySetsPort() {
    $this->assertSame($this->object, $this->object->port(0));
  }

  public function testSetCertificateFluentlySetsCertificate() {
    $this->assertSame($this->object, $this->object->certificate(''));
  }

  public function testSetConnectTimeoutFluentlySetsConnectTimeout() {
    $this->assertSame($this->object, $this->object->connectTimeout(1234));
  }

  public function testSetTimeoutFluentlySetsTimeout() {
    $this->assertSame($this->object, $this->object->timeout(1234));
  }

  public function testSetSelectTimeoutFluentlySetsTimeout() {
    $this->assertSame($this->object, $this->object->selectTimeout(1234));
  }

}
