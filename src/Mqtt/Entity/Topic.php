<?php

namespace Mqtt\Entity;

class Topic implements \Mqtt\Entity\IQoS {

  /**
   * @var \Mqtt\Entity\QoS
   */
  public $qos;

  /**
   * @var string
   */
  public $name;

  /**
   * @param \Mqtt\Entity\QoS $qos
   */
  public function __construct(\Mqtt\Entity\QoS $qos) {
    $this->qos = $qos;
    $this->qos->setRelated($this);
  }

  /**
   * @return \Mqtt\Entity\Topic
   */
  public function atMostOnce() : \Mqtt\Entity\Topic  {
    return $this->qos->atMostOnce();
  }

  /**
   * @return \Mqtt\Entity\Topic
   */
  public function atLeastOnce() : \Mqtt\Entity\Topic {
    return $this->qos->atLeastOnce();
  }

  /**
   * @return \Mqtt\Entity\Topic
   */
  public function exactlyOnce() : \Mqtt\Entity\Topic {
    return $this->qos->exactlyOnce();
  }

  /**
   * @param string $name
   * @return \Mqtt\Entity\Topic
   */
  public function name(string $name) : \Mqtt\Entity\Topic {
    $this->name = $name;
    return $this;
  }

}
