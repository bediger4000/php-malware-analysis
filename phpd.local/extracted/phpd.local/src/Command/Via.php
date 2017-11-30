<?php

namespace Clue\Psocksd\Command;

use Clue\Psocksd\ConnectionManagerLabeled;
use Clue\Psocksd\App;
use React\SocketClient\ConnectorInterface;
use \UnexpectedValueException;
use \InvalidArgumentException;
use \Exception;

class Via implements CommandInterface
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function getHelp()
    {
        return 'forward all connections via next SOCKS server';
    }

    public function run($args)
    {
        if (count($args) === 1 && $args[0] === 'list') {
            $this->runList();
        } else if (count($args) === 2 && $args[0] === 'default') {
            $this->runSetDefault($args[1]);
        } else if (count($args) === 2 && $args[0] === 'reject') {
            $this->runAdd($args[1], 'reject', -1);
        } else if ((count($args) === 3 || count($args) === 4) && $args[0] === 'add') {
            $this->runAdd($args[1], $args[2], isset($args[3]) ? $args[3] : 0);
        } else if (count($args) === 2 && $args[0] === 'remove') {
            $this->runRemove($args[1]);
        } else if (count($args) === 1 && $args[0] === 'reset') {
            $this->runReset();
        } else {
            echo (count($args) === 0 ? 'no' : 'error: invalid') . ' command arguments given. Valid options are:' . PHP_EOL;

            $this->app->getCommand('help')->dumpHelp(array(
                'list'                             => 'list all entries',
                'default <target>'                 => 'set given <target> socks proxy as default target',
                'reject <host>'                    => 'reject connections to the given host',
                'add <host> <target> [<priority>]' => 'add new <target> socks proxy for connections to given <host>',
                'remove <entryId>'                 => 'emove entry with given <id> (see "list")',
                'reset'                            => 'clear and reset everything and only connect locally'
            ));
        }
    }

    public function runList()
    {
        $cm = $this->app->getConnectionManager();

        $lengths = array(
            'id' => 3,
            'host' => 5,
            'port' => 5,
            'priority' => 5
        );

        $pad = '  ';

        $list = array();
        foreach ($cm->getConnectionManagerEntries() as $id => $entry) {
            $list [$id]= $entry;

            $entry['id'] = $id;
            foreach ($lengths as $key => &$value) {
                $l = mb_strlen($entry[$key], 'utf-8');
                if ($l > $value) {
                    $value = $l;
                }
            }
        }

        echo $this->pad('Id:', $lengths['id']) . $pad .
             $this->pad('Host:', $lengths['host']) . $pad .
             $this->pad('Port:', $lengths['port']) . $pad .
             $this->pad('Prio:', $lengths['priority']) . $pad .
             'Target:' . PHP_EOL;
        foreach ($list as $id => $entry) {
            echo $this->pad($id, $lengths['id']) . $pad .
                 $this->pad($entry['host'], $lengths['host']) . $pad .
                 $this->pad($entry['port'], $lengths['port']) . $pad .
                 $this->pad($entry['priority'], $lengths['priority']) . $pad .
                 $this->dumpConnectionManager($entry['connectionManager']) . PHP_EOL;
        }
    }

    public function runRemove($id)
    {
        $this->app->getConnectionManager()->removeConnectionManagerEntry($id);
    }

    public function runReset()
    {
        $cm = $this->app->getConnectionManager();

        // remove all connection managers
        foreach ($cm->getConnectionManagerEntries() as $id => $entry) {
            $cm->removeConnectionManagerEntry($id);
        }

        // add default connection manager
        $cm->addConnectionManagerFor($this->app->createConnectionManager('none'), '*', '*', App::PRIORITY_DEFAULT);
    }

    public function runSetDefault($socket)
    {
        try {
            $via = $this->app->createConnectionManager($socket);
        }
        catch (Exception $e) {
            echo 'error: invalid target: ' . $e->getMessage() . PHP_EOL;
            return;
        }

        // remove all CMs with PRIORITY_DEFAULT
        $cm = $this->app->getConnectionManager();
        foreach ($cm->getConnectionManagerEntries() as $id => $entry) {
            if ($entry['priority'] == App::PRIORITY_DEFAULT) {
                $cm->removeConnectionManagerEntry($id);
            }
        }

        $cm->addConnectionManagerFor($via, '*', '*', App::PRIORITY_DEFAULT);
    }

    public function runAdd($target, $socket, $priority)
    {
        try {
            $via = $this->app->createConnectionManager($socket);
        }
        catch (Exception $e) {
            echo 'error: invalid target: ' . $e->getMessage() . PHP_EOL;
            return;
        }

        try {
            $priority = $this->coercePriority($priority);
        }
        catch (Exception $e) {
            echo 'error: invalid priority: ' . $e->getMessage() . PHP_EOL;
            return;
        }

        $host = $target;
        $port = '*';

        $colon = strrpos($host, ':');

        // there is a colon and this is the only colon or there's a closing IPv6 bracket right before it
        if ($colon !== false && (strpos($host, ':') === $colon || strpos($host, ']') === ($colon - 1))) {
            $port = (int)substr($host, $colon + 1);
            $host = substr($host, 0, $colon);

            // remove IPv6 square brackets
            if (substr($host, 0, 1) === '[') {
                $host = substr($host, 1, -1);
            }
        }

        $this->app->getConnectionManager()->addConnectionManagerFor($via, $host, $port, $priority);
    }

    protected function coercePriority($priority)
    {
        $ret = filter_var($priority, FILTER_VALIDATE_FLOAT);
        if ($ret === false) {
            throw new InvalidArgumentException('Invalid priority given');
        }
        return $ret;
    }

    private function pad($str, $len)
    {
        return $str . str_repeat(' ', $len - mb_strlen($str, 'utf-8'));
    }

    protected function dumpConnectionManager(ConnectorInterface $connectionManager)
    {
        if ($connectionManager instanceof ConnectionManagerLabeled) {
            return (string)$connectionManager;
        }
        return get_class($connectionManager) . '(…)';
    }
}
