<?php declare(strict_types = 1);

namespace Mqtt\Protocol;

interface IQoS {

  const AT_MOST_ONCE = 0;
  const AT_LEAST_ONCE = 1;
  const EXACTLY_ONCE = 2;

}
