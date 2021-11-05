<?php declare(strict_types = 1);

namespace Psr\Container;

if (!interface_exists('\Psr\Container\ContainerInterface')) {
  interface ContainerInterface {
    public function get();
  }
}
