<?php declare(strict_types = 1);

namespace Mqtt\Protocol;

interface ISubscribeReturnCode {

  const SUCCESS_MAX_QOS_AT_MOST_ONCE = 0x00;
  const SUCCESS_MAX_QOS_AT_LEAST_ONCE = 0x01;
  const SUCCESS_MAX_QOS_EXACTLY_ONCE = 0x02;
  const FAILURE = 0x80;

}
