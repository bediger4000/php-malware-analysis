<?php

namespace ConnectionManager\Extra\Multiple;

class ConnectionManagerRandom extends ConnectionManagerConsecutive
{
    public function create($host, $port)
    {
        $managers = $this->managers;
        shuffle($managers);
        
        return $this->tryConnection($managers, $host, $port);
    }
}
