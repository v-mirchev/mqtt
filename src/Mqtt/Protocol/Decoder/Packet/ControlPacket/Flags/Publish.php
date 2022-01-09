<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags;

class Publish {

  const BIT_DUP = 3;
  const BIT_RETAIN = 0;
  const BIT_QOS_START = 1;
  const BIT_QOS_END = 2;

  /**
   * @var bool
   */
  protected $duplicate;

  /**
   * @var bool
   */
  protected $retain;

  /**
   * @var int
   */
  protected $qos;

  public function __construct() {
    $this->duplicate = false;
    $this->retain = false;
    $this->qos = 0;
  }

  public function __clone() {
    $this->duplicate = false;
    $this->retain = false;
    $this->qos = 0;
  }

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $frameFlags
   */
  public function decode(\Mqtt\Protocol\Binary\Data\Uint8 $frameFlags) : void {
    $this->duplicate = (bool)$frameFlags->bits()->getBit(static::BIT_DUP);
    $this->retain = (bool)$frameFlags->bits()->getBit(static::BIT_RETAIN);
    $this->qos = (int)$frameFlags->bits()->getSub(static::BIT_QOS_START, static::BIT_QOS_END)->get();

    if (!in_array($this->qos, [
      \Mqtt\Protocol\IQoS::AT_MOST_ONCE,
      \Mqtt\Protocol\IQoS::AT_LEAST_ONCE,
      \Mqtt\Protocol\IQoS::EXACTLY_ONCE,
    ])) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Publish with unklnown Qos <' . $this->qos . '>',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_QOS
      );
    }

  }

  /**
   * @return bool
   */
  public function isDuplicate(): bool {
    return $this->duplicate;
  }

  /**
   * @return bool
   */
  public function isRetain(): bool {
    return $this->retain;
  }

  /**
   * @return int
   */
  public function getQos(): int {
    return $this->qos;
  }

}

