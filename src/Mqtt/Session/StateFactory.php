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
   * @param \Psr\Container\ContainerInterface $dic
   * @param array $classMap
   */
  public function __construct(\Psr\Container\ContainerInterface $dic, array $classMap) {
    $this->dic = $dic;
    $this->classMap = $classMap;
  }

  /**
   * @param string $stateName
   * @param \Mqtt\Protocol\Protocol $protocol
   * @return \Mqtt\Session\State\IState
   * @throws \Exception
   */
  public function create(
    string $stateName,
    \Mqtt\Protocol\Protocol $protocol
  ) : \Mqtt\Session\State\IState {
    if (!isset($this->classMap[$stateName])) {
      throw new \Exception('Session state type <' . $stateName . '> not registered');
    }

    $state = clone $this->dic->get($this->classMap[$stateName]);
    $state->setProtocol($protocol);

    return $state;
  }

}
