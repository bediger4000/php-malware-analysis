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
// require authentication and hence make this a SOCKS5-only server
$server = new Server($loop, $socket);
$server->setAuthArray(array(
    'tom' => 'god',
    'user' => 'p@ssw0rd'
));

echo 'SOCKS5 server requiring authentication listening on localhost:' . $port . PHP_EOL;

$loop->run();
