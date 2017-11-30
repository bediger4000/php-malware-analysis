<?php

use ConnectionManager\Extra\Multiple\ConnectionManagerRandom;
use ConnectionManager\Extra\ConnectionManagerReject;

class ConnectionManagerRandomTest extends TestCase
{
    public function testEmpty()
    {
        $cm = new ConnectionManagerRandom();

        $promise = $cm->create('www.google.com', 80);

        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
    }

    public function testReject()
    {
        $wont = new ConnectionManagerReject();

        $cm = new ConnectionManagerRandom();
        $cm->addConnectionManager($wont);

        $promise = $cm->create('www.google.com', 80);

        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
    }
}
