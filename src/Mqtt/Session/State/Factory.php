<?php

namespace Mqtt\Session\State;

class Factory {

  /**
   * @var \Psr\Container\ContainerInterface
   */
  protected $dic;

  /**
   * @var string[]
   */
  protected $classMap;

  /**
   * @param \Psr\Container\ContainerInterface $dic
   * @param array $classMap
   */
  public function __construct(\Psr\Container\ContainerInterface $dic, array $classMap) {
    $this->dic = $dic;
    $this->classMap = $classMap;
  }

  /**
   * @param string $stateName
   * @return \Mqtt\Session\State\ISessionState
   */
  public function create(string $stateName) : \Mqtt\Session\State\ISessionState {
    if (!isset($this->classMap[$stateName])) {
      throw new \Exception('Session state type <' . $stateName . '> not registered');
    }

    return clone $this->dic->get($this->classMap[$stateName]);
  }

}
