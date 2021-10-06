<?php

namespace Mqtt\Entity\Configuration;

class Authentication {

  /**
   * @var string
   */
  public $username;

  /**
   * @var string
   */
  public $password;

  /**
   * @param string $username
   * @return \Mqtt\Entity\Configuration\Authentication
   */
  public function username(string $username) : \Mqtt\Entity\Configuration\Authentication {
    $this->username = $username;
    return $this;
  }

  /**
   * @param string $password
   * @return \Mqtt\Entity\Configuration\Authentication
   */
  public function password(string $password) : \Mqtt\Entity\Configuration\Authentication {
    $this->password = $password;
    return $this;
  }

}
