<?php

namespace Mqtt\Entity;

class TopicFilter implements \Mqtt\Entity\IQoS {

  /**
   * @var \Mqtt\Entity\QoS
   */
  public $qos;

  /**
   * @var string
   */
  public $filter;

  /**
   * @param \Mqtt\Entity\QoS $qos
   */
  public function __construct(\Mqtt\Entity\QoS $qos) {
    $this->qos = $qos;
    $this->qos->setRelated($this);
  }

  /**
   * @return \Mqtt\Entity\TopicFilter
   */
  public function atMostOnce() : \Mqtt\Entity\TopicFilter  {
    return $this->qos->atMostOnce();
  }

  /**
   * @return \Mqtt\Entity\TopicFilter
   */
  public function atLeastOnce() : \Mqtt\Entity\TopicFilter {
    return $this->qos->atLeastOnce();
  }

  /**
   * @return \Mqtt\Entity\TopicFilter
   */
  public function exactlyOnce() : \Mqtt\Entity\TopicFilter {
    return $this->qos->exactlyOnce();
  }

  /**
   * @param string $filter
   * @return \Mqtt\Entity\TopicFilter
   */
  public function filter(string $filter) : \Mqtt\Entity\TopicFilter {
    $this->filter = $filter;
    return $this;
  }

  /**
   * @param string $topic
   * @return bool
   */
  public function isMatching(string $topic) : bool {
    $regex =
      '/^' .
      str_replace(['^', '$', '/', '+', '#'], ['\^', '\$', '\/', '[^\/]*', '.*'], $this->filter) .
      '$/';

    return boolval(preg_match($regex, $topic));
  }

}
