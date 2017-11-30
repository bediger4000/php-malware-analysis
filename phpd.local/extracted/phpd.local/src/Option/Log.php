<?php

namespace Clue\Psocksd\Option;

class Log
{
    public function __construct($server)
    {
        $server->on('connection', function(\React\Socket\Connection $client) {
            $name = '#'.(int)$client->stream;
            $log = function($msg) use ($client, &$name) {
                echo date('Y-m-d H:i:s') . ' ' . $name . ' ' . $msg . PHP_EOL;
            };

            $log('connected');

            $client->on('error', function($error) use ($log) {
                $msg = $error->getMessage();
                while ($error->getPrevious() !== null) {
                    $error = $error->getPrevious();
                    $msg .= ' - ' . $error->getMessage();
                }

                $log('error: ' . $msg);
            });

            $client->on('target', function ($host, $port) use ($log) {
                $log('tunnel target: ' . $host . ':' . $port);
            });

            $client->on('auth', function($username) use ($log, &$name) {
                $name .= '(' . $username . ')';
                $log('client authenticated');
            });

            $client->on('ready', function(\React\Stream\Stream $remote) use($log) {
                $log('tunnel to remote stream #' . (int)$remote->stream . ' successfully established');
            });

            $client->on('close', function () use ($log, &$client) {
                $dump = '';
                $client->emit('dump-close', array(&$dump));

                $log('disconnected' . $dump);
            });
        });
    }
}
