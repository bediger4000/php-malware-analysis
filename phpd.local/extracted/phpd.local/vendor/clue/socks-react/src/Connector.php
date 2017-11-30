<?php

namespace Clue\React\Socks;

use React\SocketClient\ConnectorInterface;
use Clue\React\Socks\Client;

class Connector implements ConnectorInterface
{
    private $client;

    public function __construct(Client $socksClient)
    {
        $this->client = $socksClient;
    }

    public function create($host, $port)
    {
        return $this->client->getConnection($host, $port);
    }
}
