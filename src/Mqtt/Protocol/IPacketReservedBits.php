<?php declare(strict_types = 1);

namespace Mqtt\Protocol;

interface IPacketReservedBits {

  const FLAGS_CONNECT = 0b0000;
  const FLAGS_CONNACK = 0b0000;
  const FLAGS_PUBACK = 0b0000;
  const FLAGS_PUBREC = 0b0000;
  const FLAGS_PUBREL = 0b0010;
  const FLAGS_PUBCOMP = 0b0000;
  const FLAGS_SUBSCRIBE = 0b0010;
  const FLAGS_SUBACK = 0b0000;
  const FLAGS_UNSUBSCRIBE = 0b0010;
  const FLAGS_UNSUBACK = 0b0000;
  const FLAGS_PINGREQ = 0b0000;
  const FLAGS_PINGRESP = 0b0000;
  const FLAGS_DISCONNECT = 0b0000;

  const PAYLOAD_CONNECT_FLAG_BIT_0 = 0;
  const PAYLOAD_CONNACK_FLAGS_BIT_1_TO_7 = 0b0000000;
  const PAYLOAD_SUBSCRIBE_TOPIC_QOS_BIT_2_TO_7 = 0b000000;
}
