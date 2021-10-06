<?php

namespace Mqtt\Entity;

interface IQoS {

  const AT_MOST_ONCE = 0;
  const AT_LEAST_ONCE = 1;
  const EXACTLY_ONCE = 2;

  /**
   * @return \Mqtt\Entity\Message
   */
  public function atMostOnce() : IQoS;

  /**
   * @return \Mqtt\Entity\IQoS
   */
  public function atLeastOnce() : IQoS;

  /**
   * @return \Mqtt\Entity\IQoS
   */
  public function exactlyOnce() : IQoS;


}
