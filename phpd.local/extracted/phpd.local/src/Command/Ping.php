<?php

namespace Clue\Psocksd\Command;

use Clue\Psocksd\App;
use Clue\React\Socks\Client;
use React\SocketClient\Connector;
use React\SocketClient\ConnectorInterface;
use \UnexpectedValueException;
use \Exception;

class Ping implements CommandInterface
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function run($args)
    {
        if (count($args) !== 1) {
            echo 'error: command requires one argument (target socks server)'.PHP_EOL;
            return;
        }

        $socket = $args[0];
        try {
            $parsed = $this->app->parseSocksSocket($socket);
        }
        catch (Exception $e) {
            echo 'error: invalid ping target: ' . $e->getMessage() . PHP_EOL;
            return;
        }

        // TODO: remove hack
        // resolver can not resolve 'localhost' ATM
        if ($parsed['host'] === 'localhost') {
            $parsed['host'] = '127.0.0.1';
        }

        $direct = new Connector($this->app->getLoop(), $this->app->getResolver());
        $via = new Client($this->app->getLoop(), $parsed['host'], $parsed['port'], $direct, $this->app->getResolver());
        if (isset($parsed['protocolVersion'])) {
            try {
                $via->setProtocolVersion($parsed['protocolVersion']);
            }
            catch (Exception $e) {
                echo 'error: invalid protocol version: ' . $e->getMessage() . PHP_EOL;
                return;
            }
        }
        if (isset($parsed['user']) || isset($parsed['pass'])) {
            $parsed += array('user' =>'', 'pass' => '');
            try {
                $via->setAuth($parsed['user'], $parsed['pass']);
            }
            catch (Exception $e) {
                echo 'error: invalid authentication info: ' . $e->getMessage() . PHP_EOL;
                return;
            }
        }

        try {
            $via->setResolveLocal(false);
        }
        catch (UnexpectedValueException $ignore) {
            // ignore in case it's not allowed (SOCKS4 client)
        }
        $this->pingEcho($via->createConnector(), 'www.google.com', 80);
    }

    public function getHelp()
    {
        return 'ping another SOCKS proxy server via TCP handshake';
    }

    public function pingEcho(ConnectorInterface $via, $host, $port)
    {
        echo 'ping ' . $host . ':' . $port . PHP_EOL;
        return $this->ping($via, $host, $port)->then(function ($time) {
            echo 'ping test OK (⌚ ' . round($time, 3).'s)' . PHP_EOL;
            return $time;
        }, function ($error) {
            $msg = $error->getMessage();
            echo 'ping test FAILED: ' . $msg . PHP_EOL;
            throw $error;
        });
    }

    public function ping(ConnectorInterface $via, $host, $port)
    {
        $start = microtime(true);
        return $via->create($host, $port)->then(function ($stream) use ($start) {
            $stop = microtime(true);
            $stream->close();
            return ($stop - $start);
        });
    }
}
