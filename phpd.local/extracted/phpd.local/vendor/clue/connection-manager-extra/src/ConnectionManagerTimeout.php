<?php

namespace ConnectionManager\Extra;

use React\SocketClient\ConnectorInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use Exception;

class ConnectionManagerTimeout implements ConnectorInterface
{
    private $connectionManager;
    private $loop;
    private $timeout;

    public function __construct(ConnectorInterface $connectionManager, LoopInterface $loop, $timeout)
    {
        $this->connectionManager = $connectionManager;
        $this->loop = $loop;
        $this->timeout = $timeout;
    }

    public function create($host, $port)
    {
        $deferred = new Deferred();
        $timedout = false;

        $tid = $this->loop->addTimer($this->timeout, function() use ($deferred, &$timedout) {
            $deferred->reject(new Exception('Connection attempt timed out'));
            $timedout = true;
            // TODO: find a proper way to actually cancel the connection
        });

        $loop = $this->loop;
        $this->connectionManager->create($host, $port)->then(function ($connection) use ($tid, $loop, &$timedout, $deferred) {
            if ($timedout) {
                // connection successfully established but timeout already expired => close successful connection
                $connection->end();
            } else {
                $loop->cancelTimer($tid);
                $deferred->resolve($connection);
            }
        }, function ($error) use ($loop, $tid, $deferred) {
            $loop->cancelTimer($tid);
            $deferred->reject($error);
        });
        return $deferred->promise();
    }
}
