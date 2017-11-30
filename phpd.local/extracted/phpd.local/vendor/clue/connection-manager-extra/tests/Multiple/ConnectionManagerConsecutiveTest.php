<?php

use ConnectionManager\Extra\Multiple\ConnectionManagerConsecutive;
use ConnectionManager\Extra\ConnectionManagerReject;

class ConnectionManagerConsecutiveTest extends TestCase
{
    public function testEmpty()
    {
        $cm = new ConnectionManagerConsecutive();

        $promise = $cm->create('www.google.com', 80);

        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
    }

    public function testReject()
    {
        $wont = new ConnectionManagerReject();

        $cm = new ConnectionManagerConsecutive();
        $cm->addConnectionManager($wont);

        $promise = $cm->create('www.google.com', 80);

        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
    }
}
