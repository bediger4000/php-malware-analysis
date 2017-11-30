<?php

namespace ConnectionManager\Extra;

use React\SocketClient\ConnectorInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;

class ConnectionManagerDelay implements ConnectorInterface
{
    private $connectionManager;
    private $loop;
    private $delay;
    
    public function __construct(ConnectorInterface $connectionManager, LoopInterface $loop, $delay)
    {
        $this->connectionManager = $connectionManager;
        $this->loop = $loop;
        $this->delay = $delay;
    }
    
    public function create($host, $port)
    {
        $deferred = new Deferred();
        
        $connectionManager = $this->connectionManager;
        $this->loop->addTimer($this->delay, function() use ($deferred, $connectionManager, $host, $port) {
            $connectionManager->create($host, $port)->then(
                array($deferred, 'resolve'),
                array($deferred, 'reject')
            );
        });
        return $deferred->promise();
    }
}
