<?php

namespace Mqtt\Session;

interface ISessionContext {

  public function getProtocol() : \Mqtt\Protocol\IProtocol;
  public function getIdProvider() : \Mqtt\IPacketIdProvider;

}
