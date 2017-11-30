<?php

namespace Clue\Psocksd;

use Clue\React\Socks\Client;
use React\SocketClient\Connector;
use React\SocketClient\ConnectorInterface;
use ConnectionManager\Extra\Multiple\ConnectionManagerSelective;
use ConnectionManager\Extra\ConnectionManagerReject;
use \InvalidArgumentException;
use \Exception;

class App
{
    private $server;
    private $loop;
    private $resolver;
    private $via;
    private $commands;

    const PRIORITY_DEFAULT = 100;

    public function __construct()
    {
        $this->commands = array(
            'help'   => new Command\Help($this),
            'status' => new Command\Status($this),
            'via'    => new Command\Via($this),
            'ping'   => new Command\Ping($this),
            'quit'   => new Command\Quit($this)
        );
    }

    public function run()
    {
        $measureTraffic = true;
        $measureTime = true;

        $socket = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : 'socks://localhost:9050';

        $settings = $this->parseSocksSocket($socket);

        if ($settings['host'] === '*') {
            $settings['host'] = '0.0.0.0';
        }


        $this->loop = $loop = \React\EventLoop\Factory::create();

        $dnsResolverFactory = new \React\Dns\Resolver\Factory();
        $this->resolver = $dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

        $this->via = new ConnectionManagerSelective();
        $this->via->addConnectionManagerFor($this->createConnectionManager('none'), '*', '*', self::PRIORITY_DEFAULT);

        $socket = new \React\Socket\Server($loop);

        $this->server = new \Clue\React\Socks\Server($loop, $socket, $this->via);

        if (isset($settings['protocolVersion'])) {
            $this->server->setProtocolVersion($settings['protocolVersion']);
        }

        $socket->listen($settings['port'], $settings['host']);

        if (isset($settings['user']) || isset($settings['pass'])) {
            $settings += array('user' => '', 'pass' => '');
            $this->server->setAuthArray(array(
                $settings['user'] => $settings['pass']
            ));
        }

        new Option\Log($this->server);

        if ($measureTraffic) {
            new Option\MeasureTraffic($this->server);
        }

        if ($measureTime) {
            new Option\MeasureTime($this->server);
        }

        echo 'SOCKS proxy server listening on ' . $settings['host'] . ':' . $settings['port'] . PHP_EOL;

        if (defined('STDIN') && is_resource(STDIN)) {
            $that = $this;
            $loop->addReadStream(STDIN, function() use ($that) {
                $line = trim(fgets(STDIN, 4096));
                $that->onReadLine($line);
            });
        }

        $loop->run();
    }

    public function onReadLine($line)
    {
        // nothing entered => skip input
        if ($line === '') {
            return;
        }

        // TODO: properly parse command and its arguments (respect quotes, etc.)
        $args = explode(' ', $line);
        $command = array_shift($args);

        if (isset($this->commands[$command])) {
            $this->commands[$command]->run($args);
        } else {
            echo 'invalid command. type "help"?' . PHP_EOL;
        }
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     *
     * @return React\EventLoop\LoopInterface
     */
    public function getLoop()
    {
        return $this->loop;
    }

    public function getCommands()
    {
        return $this->commands;
    }

    /**
     *
     * @param string $command
     * @return Command\CommandInterface
     * @throws Exception
     */
    public function getCommand($command)
    {
        if (!isset($this->commands[$command])) {
            throw new Exception('Invalid command given');
        }
        return $this->commands[$command];
    }

    /**
     * @return \ConnectionManager\Extra\Multiple\ConnectionManagerSelective
     */
    public function getConnectionManager()
    {
        return $this->via;
    }

    public function createConnectionManager($socket)
    {
        if ($socket === 'reject') {
            echo 'reject' . PHP_EOL;
            return new ConnectionManagerLabeled(new ConnectionManagerReject(), '-reject-');
        }
        $direct = new Connector($this->loop, $this->resolver);
        if ($socket === 'none') {
            $via = new ConnectionManagerLabeled($direct, '-direct-');

            echo 'use direct connection to target' . PHP_EOL;
        } else {
            $parsed = $this->parseSocksSocket($socket);

            // TODO: remove hack
            // resolver can not resolve 'localhost' ATM
            if ($parsed['host'] === 'localhost') {
                $parsed['host'] = '127.0.0.1';
            }

            $via = new Client($this->loop, $parsed['host'], $parsed['port'], $direct, $this->resolver);
            if (isset($parsed['protocolVersion'])) {
                try {
                    $via->setProtocolVersion($parsed['protocolVersion']);
                }
                catch (Exception $e) {
                    throw new Exception('invalid protocol version: ' . $e->getMessage());
                }
            }
            if (isset($parsed['user']) || isset($parsed['pass'])) {
                $parsed += array('user' =>'', 'pass' => '');
                try {
                    $via->setAuth($parsed['user'], $parsed['pass']);
                }
                catch (Exception $e) {
                    throw new Exception('invalid authentication info: ' . $e->getMessage());
                }
            }

            echo 'use '.$this->reverseSocksSocket($parsed) . ' as next hop';

            try {
                $via->setResolveLocal(false);
                echo ' (resolve remotely)';
            }
            catch (UnexpectedValueException $ignore) {
                // ignore in case it's not allowed (SOCKS4 client)
                echo ' (resolve locally)';
            }

            $via = new ConnectionManagerLabeled($via->createConnector(), $this->reverseSocksSocket($parsed));

            echo PHP_EOL;
        }
        return $via;
    }

    // $socket = 9050;
    // $socket = 'socks://me@localhost:9050';
    // $socket = 'localhost:9050';
    public function parseSocksSocket($socket)
    {
        // workaround parsing plain port numbers
        if (preg_match('/^\d+$/', $socket)) {
            $parts = array('port' => (int)$socket);
        } else {
            // workaround for incorrect parsing when scheme is missing
            $parts = parse_url((strpos($socket, '://') === false ? 'socks://' : '') . $socket);
            if (!$parts) {
                throw new InvalidArgumentException('Invalid/unparsable socket given');
            }
        }
        if (isset($parts['path']) || isset($parts['query']) || isset($parts['frament'])) {
            throw new InvalidArgumentException('Invalid socket given');
        }

        $parts += array('scheme' => 'socks', 'host' => 'localhost', 'port' => 9050);

        if (preg_match('/^socks(\d\w?)?$/', $parts['scheme'], $match)) {
            if (isset($match[1])) {
                $parts['protocolVersion'] = $match[1];
            }
        } else {
            throw new InvalidArgumentException('Invalid socket scheme given');
        }

        return $parts;
    }

    public function reverseSocksSocket($parts)
    {
        $ret = $parts['scheme'] . '://';
        if (isset($parts['user']) || isset($parts['pass'])) {
            $parts += array('user' => '', 'pass' => '');
            $ret .= $parts['user'] . ':' . $parts['pass'] . '@';
        }
        $ret .= $parts['host'] . ':' . $parts['port'];
        return $ret;
    }
}
