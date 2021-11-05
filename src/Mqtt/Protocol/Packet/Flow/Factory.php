<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow;

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
   * @var \Mqtt\Protocol\Packet\Flow\ISessionContext
   */
  protected $context;

  /**
   * @param \Psr\Container\ContainerInterface $dic
   * @param array $classMap
   * @param \Mqtt\Protocol\Packet\Flow\ISessionContext $context
   */
  public function __construct(
    \Psr\Container\ContainerInterface $dic,
    array $classMap,
    \Mqtt\Protocol\Packet\Flow\ISessionContext $context
  ) {
    $this->dic = $dic;
    $this->classMap = $classMap;
    $this->context = $context;
  }

  /**
   * @param string $stateName
   * @return Mqtt\Protocol\Packet\Flow\IState
   */
  public function create(string $stateName) : \Mqtt\Protocol\Packet\Flow\IState {
    if (!isset($this->classMap[$stateName])) {
      throw new \Exception('Packet flow state type <' . $stateName . '> not registered');
    }

    /* @var $state \Mqtt\Protocol\Packet\Flow\IState */
    $state = clone $this->dic->get($this->classMap[$stateName]);
    $state->setContext($this->context);

    return $state;
  }

}
