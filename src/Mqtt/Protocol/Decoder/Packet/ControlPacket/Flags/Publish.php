<?php

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags;

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
  public function decode(\Mqtt\Protocol\Binary\Data\Uint8 $frameFlags) : void {
    $this->dup = (bool)$frameFlags->bits()->getBit(static::BIT_DUP);
    $this->retain = (bool)$frameFlags->bits()->getBit(static::BIT_RETAIN);
    $this->qos = (int)$frameFlags->bits()->getSub(static::BIT_QOS_START, static::BIT_QOS_END)->get();

    if ($this->qos > 2) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Incorrect packet QoS received in PUBLISH',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_QOS
      );
    }
  }

}

