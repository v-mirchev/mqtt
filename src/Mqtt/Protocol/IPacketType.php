<?php declare(strict_types = 1);

namespace Mqtt\Protocol;

interface IPacketType {

  const CONNECT = 0x1;
  const CONNACK = 0x2;
  const PUBLISH = 0x3;
  const PUBACK = 0x4;
  const PUBREC = 0x5;
  const PUBREL = 0x6;
  const PUBCOMP = 0x7;
  const SUBSCRIBE = 0x8;
  const SUBACK = 0x9;
  const UNSUBSCRIBE = 0xA;
  const UNSUBACK = 0xB;
  const PINGREQ = 0xC;
  const PINGRESP = 0xD;
  const DISCONNECT = 0xE;

}
