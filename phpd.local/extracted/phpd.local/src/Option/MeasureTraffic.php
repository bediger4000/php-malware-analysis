<?php

namespace Clue\Psocksd\Option;

class MeasureTraffic
{
    public function __construct($server)
    {
        $server->on('connection', function(\React\Socket\Connection $client) {
            $client->on('ready', function(\React\Stream\Stream $remote) use($client) {
                $up = $down = 0;

                $client->on('data', function($data) use (&$up) {
                    $up += strlen($data);
                });

                $remote->on('data', function($data) use (&$down) {
                    $down += strlen($data);
                });

                $client->on('dump-close', function (&$dump) use (&$up, &$down) {
                    $dump .= ' (traffic: ' . $down . 'B⤓/' . $up . 'B↥)';
                });
            });
        });
    }
}
