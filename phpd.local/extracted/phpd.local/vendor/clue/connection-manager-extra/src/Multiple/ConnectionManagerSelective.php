<?php

namespace ConnectionManager\Extra\Multiple;

use React\SocketClient\ConnectorInterface;
use React\Promise\Deferred;
use \UnderflowException;
use \InvalidArgumentException;

class ConnectionManagerSelective implements ConnectorInterface
{
    const MATCH_ALL = '*';

    private $targets = array();

    public function create($host, $port)
    {
        try {
            $cm = $this->getConnectionManagerFor($host, $port);
        }
        catch (UnderflowException $e) {
            $deferred = new Deferred();
            $deferred->reject($e);
            return $deferred->promise();
        }
        return $cm->create($host, $port);
    }

    public function addConnectionManagerFor($connectionManager, $targetHost=self::MATCH_ALL, $targetPort=self::MATCH_ALL, $priority=0)
    {
        $this->targets []= array(
            'connectionManager' => $connectionManager,
            'matchHost' => $this->createMatcherHost($targetHost),
            'matchPort' => $this->createMatcherPort($targetPort),
            'host'      => $targetHost,
            'port'      => $targetPort,
            'priority'  => $priority
        );

        // return the key as new entry ID
        end($this->targets);
        $id = key($this->targets);

        // sort array by priority
        $targets =& $this->targets;
        uksort($this->targets, function ($a, $b) use ($targets) {
            $pa = $targets[$a]['priority'];
            $pb = $targets[$b]['priority'];
            return ($pa < $pb ? -1 : ($pa > $pb ? 1 : ($a - $b)));
        });

        return $id;
    }

    public function getConnectionManagerEntries()
    {
        return $this->targets;
    }

    public function removeConnectionManagerEntry($id)
    {
        unset($this->targets[$id]);
    }

    public function getConnectionManagerFor($targetHost, $targetPort)
    {
        foreach ($this->targets as $target) {
            if ($target['matchPort']($targetPort) && $target['matchHost']($targetHost)) {
                return $target['connectionManager'];
            }
        }
        throw new UnderflowException('No connection manager for given target found');
    }

    // *
    // singlePort
    // startPort - targetPort
    // port1, port2, port3
    // startPort - targetPort, portAdditional
    public function createMatcherPort($pattern)
    {
        if ($pattern === self::MATCH_ALL) {
            return function() {
                return true;
            };
        } else if (strpos($pattern, ',') !== false) {
            $checks = array();
            foreach (explode(',', $pattern) as $part) {
                $checks []= $this->createMatcherPort(trim($part));
            }
            return function ($port) use ($checks) {
                foreach ($checks as $check) {
                    if ($check($port)) {
                        return true;
                    }
                }
                return false;
            };
        } else if (preg_match('/^(\d+)$/', $pattern, $match)) {
            $single = $this->coercePort($match[1]);
            return function ($port) use ($single) {
                return ($port == $single);
            };
        } else if (preg_match('/^(\d+)\s*\-\s*(\d+)$/', $pattern, $match)) {
            $start = $this->coercePort($match[1]);
            $end   = $this->coercePort($match[2]);
            if ($start >= $end) {
                throw new InvalidArgumentException('Invalid port range given');
            }
            return function($port) use ($start, $end) {
                return ($port >= $start && $port <= $end);
            };
        } else {
             throw new InvalidArgumentException('Invalid port matcher given');
        }
    }

    private function coercePort($port)
    {
        // TODO: check 0-65535
        return (int)$port;
    }

    // *
    // targetHostname
    // targetIp
    // targetHostname, otherTargetHostname, anotherTargetHostname
    // TODO: targetIp/netmaskNum
    // TODO: targetIp/netmaskIp
    public function createMatcherHost($pattern)
    {
        if ($pattern === self::MATCH_ALL) {
            return function() {
                return true;
            };
        } else if (strpos($pattern, ',') !== false) {
            $checks = array();
            foreach (explode(',', $pattern) as $part) {
                $checks []= $this->createMatcherHost(trim($part));
            }
            return function ($host) use ($checks) {
                foreach ($checks as $check) {
                    if ($check($host)) {
                        return true;
                    }
                }
                return false;
            };
        } else if (is_string($pattern)) {
            $pattern = strtolower($pattern);
            return function($target) use ($pattern) {
                return fnmatch($pattern, strtolower($target));
            };
        } else {
            throw new InvalidArgumentException('Invalid host matcher given');
        }
    }
}
