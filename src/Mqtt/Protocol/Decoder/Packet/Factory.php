<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet;

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
   * @param int $packetType
   * @return \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder
   */
  public function create(int $packetType) : \Mqtt\Protocol\Decoder\Packet\IControlPacketDecoder {
    if (!isset($this->classMap[$packetType])) {
      throw new \Mqtt\Exception\ProtocolViolation('Packet type <' . $packetType . '> not registered');
    }

    return clone $this->dic->get($this->classMap[$packetType]);
  }

}
