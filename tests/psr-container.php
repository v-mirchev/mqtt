<?php
namespace Psr\Container;

if (!interface_exists('\Psr\Container\ContainerInterface')) {
  interface ContainerInterface {
    public function get();
  }
}
