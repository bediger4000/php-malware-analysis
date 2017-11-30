<?php

use Clue\React\Socks\Server;
use React\Socket\Server as Socket;

include_once __DIR__.'/../vendor/autoload.php';

$port = isset($argv[1]) ? $argv[1] : 9050;

$loop = React\EventLoop\Factory::create();

// listen on localhost:$port
$socket = new Socket($loop);
$socket->listen($port,'localhost');

// start a new server listening for incoming connection on the given socket
$server = new Server($loop, $socket);

echo 'SOCKS server listening on localhost:' . $port . PHP_EOL;

$loop->run();
