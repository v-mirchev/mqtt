<?php

namespace Mqtt\Protocol;

interface IPacket {

  const CONNECT = 1;
  const CONNACK = 2;
  const PUBLISH = 3;
  const PUBACK = 4;
  const PUBREC = 5;
  const PUBREL = 6;
  const PUBCOMP = 7;
  const SUBSCRIBE = 8;
  const SUBACK = 9;
  const UNSUBSCRIBE = 10;
  const UNSUBACK = 11;
  const PINGREQ = 12;
  const PINGRESP = 13;
  const DISCONNECT = 14;

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function encode(\Mqtt\Protocol\Binary\Frame $frame);

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Binary\Frame $frame);

}
