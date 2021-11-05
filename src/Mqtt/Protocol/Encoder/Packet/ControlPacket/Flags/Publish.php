<?php

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags;

class Publish {

  const BIT_DUP = 3;
  const BIT_RETAIN = 0;
  const BIT_QOS_START = 1;
  const BIT_QOS_END = 2;

  /**
   * @var bool
   */
  public $dup;

  /**
   * @var bool
   */
  public $retain;

  /**
   * @var int
   */
  public $qos;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $frameFlags
   */
  public function encode(\Mqtt\Protocol\Binary\Data\Uint8 $frameFlags) : void {
    if ($this->qos > 2) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Incorrect packet QoS received in PUBLISH',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_QOS
      );
    }

    $frameFlags->bits()->setBit(static::BIT_DUP, $this->dup);
    $frameFlags->bits()->setBit(static::BIT_RETAIN, $this->retain);
    $frameFlags->bits()->setSub(static::BIT_QOS_START, static::BIT_QOS_END, $this->qos);
  }

}
