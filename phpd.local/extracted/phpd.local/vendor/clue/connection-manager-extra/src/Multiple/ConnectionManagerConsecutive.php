<?php

namespace ConnectionManager\Extra\Multiple;

use React\SocketClient\ConnectorInterface;
use React\Promise\Deferred;
use \UnderflowException;

class ConnectionManagerConsecutive implements ConnectorInterface
{
    protected $managers = array();

    public function addConnectionManager(ConnectorInterface $connectionManager)
    {
        $this->managers []= $connectionManager;
    }

    public function create($host, $port)
    {
        return $this->tryConnection($this->managers, $host, $port);
    }

    /**
     *
     * @param ConnectorInterface[] $managers
     * @param string $host
     * @param int $port
     * @return Promise
     * @internal
     */
    public function tryConnection(array $managers, $host, $port)
    {
        if (!$managers) {
            $deferred = new Deferred();
            $deferred->reject(new UnderflowException('No more managers to try to connect through'));
            return $deferred->promise();
        }
        $manager = array_shift($managers);
        $that = $this;
        return $manager->create($host,$port)->then(null, function() use ($that, $managers, $host, $port) {
            // connection failed, re-try with remaining connection managers
            return $that->tryConnection($managers, $host, $port);
        });
    }
}
