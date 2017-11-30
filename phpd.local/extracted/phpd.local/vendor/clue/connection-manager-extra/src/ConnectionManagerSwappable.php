<?php

namespace ConnectionManager\Extra;

use React\SocketClient\ConnectorInterface;

// connection manager decorator which simplifies exchanging the actual connection manager during runtime
class ConnectionManagerSwappable implements ConnectorInterface
{
    protected $connectionManager;

    public function __construct(ConnectorInterface $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }

    public function create($host, $port)
    {
        return $this->connectionManager->create($host, $port);
    }

    public function setConnectionManager(ConnectorInterface $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }
}
