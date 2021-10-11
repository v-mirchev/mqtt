<?php

return [

  'mqtt.auth.password' => '',
  'mqtt.auth.username' => '',

  'mqtt.server.host' => '127.0.0.1',
  'mqtt.server.port' => 1883,

  'mqtt.server.connectTimeout' => 60000,
  'mqtt.server.timeout' => 30000,

  'mqtt.session.clientId' => 'pure-mqtt',
  'mqtt.session.keepAliveInterval' => 60,
  'mqtt.session.certificateFile' => null,
  'mqtt.session.will.topic' => 'lib-will-topic',
  'mqtt.session.will.content' => 'lib-will-content',
  'mqtt.session.will.retain' => true,

];
