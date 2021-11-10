<?php declare(strict_types = 1);

namespace Mqtt\Entity;

class QoS implements \Mqtt\Entity\IQoS {

  /**
   * @var int
   */
  public $qos;

  /**
   * @var \Mqtt\Entity\IQoS
   */
  protected $relatedEntity;

  /**
   * @return \Mqtt\Entity\IQoS
   */
  public function atMostOnce() : \Mqtt\Entity\IQoS  {
    $this->qos = \Mqtt\Entity\IQoS::AT_MOST_ONCE;
    return $this->relatedEntity;
  }

  /**
   * @return \Mqtt\Entity\IQoS
   */
  public function atLeastOnce() : \Mqtt\Entity\IQoS {
    $this->qos = \Mqtt\Entity\IQoS::AT_LEAST_ONCE;
    return $this->relatedEntity;
  }

  /**
   * @return \Mqtt\Entity\IQoS
   */
  public function exactlyOnce() : \Mqtt\Entity\IQoS {
    $this->qos = \Mqtt\Entity\IQoS::EXACTLY_ONCE;
    return $this->relatedEntity;
  }

  /**
   * @param \Mqtt\Entity\IQoS $relatedEntity
   * @return \Mqtt\Entity\IQoS
   */
  public function setRelated(\Mqtt\Entity\IQoS $relatedEntity) : void {
    $this->relatedEntity = $relatedEntity;
  }

}
