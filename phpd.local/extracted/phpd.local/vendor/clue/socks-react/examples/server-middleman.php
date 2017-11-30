<?php

use Clue\React\Socks\Client;
use Clue\React\Socks\Server;
use React\Socket\Server as Socket;

include_once __DIR__.'/../vendor/autoload.php';

$myPort = isset($argv[1]) ? $argv[1] : 9051;
$otherPort = isset($argv[2]) ? $argv[2] : 9050;

$loop = React\EventLoop\Factory::create();

// set next SOCKS server localhost:$otherPort as target
$target = new Client($loop, '127.0.0.1', $otherPort);
$target->setAuth('user','p@ssw0rd');

// listen on localhost:$myPort
$socket = new Socket($loop);
$socket->listen($myPort, 'localhost');

// start a new server which forwards all connections to the other SOCKS server
$server = new Server($loop, $socket, $target->createConnector());

echo 'SOCKS server listening on localhost:' . $myPort . ' (which forwards everything to target SOCKS server 127.0.0.1:' . $otherPort . ')' . PHP_EOL;
echo 'Not already running the target SOCKS server? Try this: php server-auth.php ' . $otherPort . PHP_EOL;

$loop->run();
