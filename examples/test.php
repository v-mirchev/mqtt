<?php declare(ticks = 1); declare(strict_types = 1);


set_time_limit(0);

/* @var $container \DI\Container */
$container = require_once __DIR__ . '/bootstrap/bootstrap.php';

function stringToStringStream(string $string) : string {
  return implode('',
    array_map('chr',
      array_map(function ($value) { return intval($value, 16); }, str_split(
        str_replace(' ', '', $string), 2)
  )));
}

function stringToHex($string) {
  echo implode(' ',
    array_map('dechex',
    array_map('ord', str_split($string))
  ));
  echo PHP_EOL;
}


/* @var $codec \Mqtt\Protocol\ICodec */
$codec = $container->get(\Mqtt\Protocol\ICodec::class);

$codec->onDecodingCompleted(function (\Mqtt\Protocol\Entity\Packet\IPacket $packet) {
  var_dump($packet);
});

$codec->decode(stringToStringStream('22 02 01 05'));
$codec->decode(stringToStringStream('D0 00'));
$codec->decode(stringToStringStream('40 02 01 01'));

$packet = new \Mqtt\Protocol\Entity\Packet\Subscribe();
$packet->setId(3);
$packet->topics['TOPIC2'] = 3;
$packet->topics['TOPIC1'] = 2;

$codec->onEncodingCompleted(function (string $data) {
  stringToHex($data);
});
$codec->encode($packet);
