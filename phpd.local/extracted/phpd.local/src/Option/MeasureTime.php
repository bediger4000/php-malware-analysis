<?php

namespace Clue\Psocksd\Option;

class MeasureTime
{
    public function __construct($server)
    {
        $server->on('connection', function(\React\Socket\Connection $client) {
            $start = microtime(true);

            $client->on('dump-close', function (&$dump) use ($start) {
                $stop = microtime(true);
                $dump .= ' (⌚ ' . round($stop - $start,3).'s)';
            });
        });
    }
}
