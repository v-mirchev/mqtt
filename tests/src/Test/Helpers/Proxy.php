<?php

namespace Test\Helpers;

class Proxy {

  /**
   * @var \PHPUnit\Framework\TestCase
   */
  protected $assert;

  /**
   * @var object
   */
  protected $sut;

  /**
   * @var string
   */
  protected $sutMethod;

  /**
   * @var array
   */
  protected $sutArguments;

  /**
   * @var mixed
   */
  protected $returnValue;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $proxy;

  /**
   * @var string
   */
  protected $proxyMethod;

  /**
   * @var array
   */
  protected $proxyArguments = null;

  /**
   * @var mixed
   */
  protected $proxyReturnValue = null;

  /**
   * @var bool
   */
  protected $useOneToOne = false;

  /**
   * @param \PHPUnit\Framework\Assert $assert
   */
  public function __construct(\PHPUnit\Framework\Assert $assert) {
    $this->assert = $assert;
  }

  /**
   * @param object $sut
   * @return $this
   */
  public function setSut(object $sut) {
    $this->sut = $sut;
    return $this;
  }

  /**
   * @param string $sutMethod
   * @return $this
   */
  public function method(string $sutMethod) {
    $this->sutMethod = $sutMethod;
    return $this;
  }

  /**
   * @param array $sutArguments
   * @return $this
   */
  public function arguments(array $sutArguments) {
    $this->sutArguments = $sutArguments;
    return $this;
  }

  /**
   * @param mixed $returnValue
   * @return $this
   */
  public function returnValue($returnValue) {
    $this->returnValue = $returnValue;
    return $this;
  }

  /**
   * @param \PHPUnit\Framework\MockObject\MockObject $proxy
   * @return $this
   */
  public function with(\PHPUnit\Framework\MockObject\MockObject $proxy) {
    $this->proxy = $proxy;
    return $this;
  }

  /**
   * @param string $proxyMethod
   * @return $this
   */
  public function proxyMethod(string $proxyMethod) {
    $this->useOneToOne = false;
    $this->proxyMethod = $proxyMethod;
    return $this;
  }

  /**
   * @param mixed $proxyArguments
   * @return $this
   */
  public function proxyArguments($proxyArguments) {
    $this->useOneToOne = false;
    $this->proxyArguments = $proxyArguments;
    return $this;
  }

  /**
   * @param mixed $proxyReturnValue
   * @return $this
   */
  public function proxyReturnValue($proxyReturnValue) {
    $this->useOneToOne = false;
    $this->proxyReturnValue = $proxyReturnValue;
    return $this;
  }

  /**
   * @param mixed[] $arguments
   * @return \PHPUnit\Framework\Constraint\IsEqual[]
   */
  protected function argumentsToConstraints(array $arguments) {
    $constraints = [];
    foreach ($arguments as $argument) {
      $constraints[] = $this->assert->equalTo($argument);
    }

    return $constraints;
  }

  /**
   * @return void
   */
  public function assert(): void {

    $methodCalExpectation = $this->proxy->
      expects($this->assert->once());

    if ($this->proxyMethod) {
      $methodCalExpectation->method($this->proxyMethod);
    } else {
      $methodCalExpectation->method($this->sutMethod);
    }

    if ($this->proxyArguments) {
      call_user_func_array([$methodCalExpectation, 'with'], $this->argumentsToConstraints($this->proxyArguments));
    } elseif ($this->sutArguments) {
      call_user_func_array([$methodCalExpectation, 'with'], $this->argumentsToConstraints($this->sutArguments));
    }

    if ($this->proxyReturnValue) {
      $methodCalExpectation->will($this->assert->returnValue($this->proxyReturnValue));
    } elseif ($this->returnValue) {
      $methodCalExpectation->will($this->assert->returnValue($this->returnValue));
    }

    /** @BUG PHPUnit 6.4 marks this test as Risky although trying with never() fails * */
    $actualValue = call_user_func_array([$this->sut, $this->sutMethod], $this->sutArguments  ? $this->sutArguments : []);

    if ($this->returnValue) {
      $this->assert->assertEquals($actualValue, $this->returnValue);
    } else {
      /** @BUG-WORKAROUND * */
      $this->assert->assertTrue(true);
    }
  }

}
