<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame\Fsm;

class Factory {

  /**
   * @var \Psr\Container\ContainerInterface
   */
  protected $continer;

  /**
   * @var string[]
   */
  protected $classMap;

  /**
   * @param \Psr\Container\ContainerInterface $continer
   * @param array $classMap
   */
  public function __construct(
    \Psr\Container\ContainerInterface $continer,
    array $classMap
  ) {
    $this->continer = $continer;
    $this->classMap = $classMap;
  }

  /**
   * @param string $stateName
   * @return \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState
   * @throws \Exception
   */
  public function create(string $stateName) : \Mqtt\Protocol\Decoder\Frame\Fsm\State\IState {
    if (!isset($this->classMap[$stateName])) {
      throw new \Exception('Frame state type <' . $stateName . '> not registered');
    }

    /* @var $state \Mqtt\Protocol\Decoder\Frame\State\IState */
    $state = clone $this->continer->get($this->classMap[$stateName]);

    return $state;
  }

}
