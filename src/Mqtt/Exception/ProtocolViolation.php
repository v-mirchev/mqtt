<?php

namespace Mqtt\Exception;

class ProtocolViolation extends \Exception {
  const INCORRECT_PACKET_TYPE = 1;
  const INCORRECT_CONTROL_HEADER_RESERVED_BITS = 2;
  const INCORRECT_PAYLOAD_RESERVED_BITS = 3;
  const UNKNOWN_PAYLOAD_DATA = 4;
  const INCORRECT_QOS = 5;

  const INCORRECT_CONNACK_RETURN_CODE = 101;
}
