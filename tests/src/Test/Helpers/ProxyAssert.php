<?php

namespace Test\Helpers;

trait ProxyAssert {

  public function proxy(object $sut) : Proxy {
    $proxy = new Proxy($this);
    $proxy->setSut($sut);
    return $proxy;
  }

}
