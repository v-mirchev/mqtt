<?php declare(ticks = 1);

set_time_limit(0);

/* @var $container \DI\Container */
$container = require_once __DIR__ . '/bootstrap/bootstrap.php';


/* @var $client \Mqtt\Client */
$client = $container->get(\Mqtt\Client::class);
$client->start();
