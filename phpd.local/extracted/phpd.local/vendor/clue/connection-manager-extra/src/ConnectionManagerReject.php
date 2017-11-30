<?php

namespace ConnectionManager\Extra;

use React\SocketClient\ConnectorInterface;
use React\Promise\Deferred;
use \Exception;

// a simple connection manager that rejects every single connection attempt
class ConnectionManagerReject implements ConnectorInterface
{
    public function create($host, $port)
    {
        $deferred = new Deferred();
        $deferred->reject(new Exception('Connection rejected'));
        return $deferred->promise();
    }
}
