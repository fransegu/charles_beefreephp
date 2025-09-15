<?php
require('../../dmxConnectLib/dmxConnect.php');


$app = new \lib\App();

$app->define(<<<'JSON'
[
  {
    "name": "api",
    "module": "api",
    "action": "send",
    "options": {
      "url": "https://auth.getbee.io/loginV2",
      "headers": {
        "Accept": "application/json",
        "Content-Type": "application/json"
      },
      "method": "POST",
      "dataType": "json",
      "passErrors": false,
      "data": {
        "client_id": "a785b377-7a6c-4b11-80c9-6546733fc186",
        "client_secret": "hElITunwgHNVfLW64mfUahjzpa3oS4exCyal2kFyPuIZUQnp1Tnj",
        "uid": "demo-user-123"
      }
    },
    "collapsed": true
  },
  {
    "name": "access_token",
    "module": "core",
    "action": "setvalue",
    "options": {
      "value": "{{api.data.access_token}}"
    },
    "meta": [],
    "outputType": "text",
    "output": true
  },
  {
    "name": "v2",
    "module": "core",
    "action": "setvalue",
    "options": {
      "value": true
    },
    "meta": [],
    "output": true,
    "outputType": "text"
  }
]
JSON
);
?>