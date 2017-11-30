<?php

namespace Clue\Psocksd;

use React\SocketClient\ConnectorInterface;

class ConnectionManagerLabeled implements ConnectorInterface
{
    private $connectionManager;
    private $label;

    public function __construct(ConnectorInterface $connectionManager, $label)
    {
        $this->connectionManager = $connectionManager;
        $this->label = $label;
    }

    public function create($host, $port)
    {
        return $this->connectionManager->create($host, $port);
    }

    public function __toString()
    {
        return $this->label;
    }
}
