<?php

namespace Clue\Psocksd\Command;

interface CommandInterface
{
    public function run($args);

    public function getHelp();
}
