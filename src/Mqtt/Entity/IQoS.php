<?php declare(strict_types = 1);

namespace Mqtt\Entity;

interface IQoS {

  const AT_MOST_ONCE = 0;
  const AT_LEAST_ONCE = 1;
  const EXACTLY_ONCE = 2;

  /**
   * @return \Mqtt\Entity\IQoS
   */
  public function atMostOnce() : \Mqtt\Entity\IQoS;

  /**
   * @return \Mqtt\Entity\IQoS
   */
  public function atLeastOnce() : \Mqtt\Entity\IQoS;

  /**
   * @return \Mqtt\Entity\IQoS
   */
  public function exactlyOnce() : \Mqtt\Entity\IQoS;


}
