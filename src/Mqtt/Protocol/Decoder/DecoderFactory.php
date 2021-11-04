<?php

namespace Mqtt\Protocol\Decoder;

class DecoderFactory {

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
   * @param int $packetType
   * @return \Mqtt\Protocol\Decoder\IPacketDecoder
   */
  public function create(int $packetType) : \Mqtt\Protocol\Decoder\IPacketDecoder {
    if (!isset($this->classMap[$packetType])) {
      throw new \Exception('Packet type <' . $packetType . '> not registered');
    }

    return clone $this->dic->get($this->classMap[$packetType]);
  }

}
