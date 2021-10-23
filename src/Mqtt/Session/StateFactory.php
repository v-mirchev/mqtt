<?php

namespace Mqtt\Session;

class StateFactory {

  /**
   * @var \Psr\Container\ContainerInterface
   */
  protected $dic;

  /**
   * @var string[]
   */
  protected $classMap;

  /**
   * @var \Mqtt\Session\Context
   */
  protected $context;

  /**
   * @param \Psr\Container\ContainerInterface $dic
   * @param array $classMap
   * @param \Mqtt\Session\Context $context
   */
  public function __construct(
    \Psr\Container\ContainerInterface $dic,
    array $classMap,
    \Mqtt\Session\Context $context
  ) {
    $this->dic = $dic;
    $this->classMap = $classMap;
    $this->context = $context;
  }

  /**
   * @param string $stateName
   * @return \Mqtt\Session\State\IState
   * @throws \Exception
   */
  public function create(string $stateName) : \Mqtt\Session\State\IState {
    if (!isset($this->classMap[$stateName])) {
      throw new \Exception('Session state type <' . $stateName . '> not registered');
    }

    /* @var $state \Mqtt\Session\State\IState */
    $state = clone $this->dic->get($this->classMap[$stateName]);
    $state->setContext($this->context);

    return $state;
  }

}
