<?php

namespace Clue\Psocksd\Command;

use Clue\Psocksd\App;

class Help implements CommandInterface
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function run($args)
    {
        echo 'psocksd help:' . PHP_EOL;
        $this->dumpCommands($this->app->getCommands());
    }

    public function dumpCommands($commands)
    {
        $help = array();
        foreach ($commands as $name => $command) {
            $help[$name] = $command->getHelp();
        }
        return $this->dumpHelp($help);
    }

    public function dumpHelp($help)
    {
        foreach ($help as $name => $info) {
            echo '    ' . $name . PHP_EOL .
                 '        ' . $info . PHP_EOL;
        }
    }

    public function getHelp()
    {
        return 'show this very help';
    }
}
