<?php

use ConnectionManager\Extra\ConnectionManagerSwappable;
use ConnectionManager\Extra\ConnectionManagerReject;

class ConnectionManagerSwappableTest extends TestCase
{
    public function testSwap()
    {
        $wont = new ConnectionManagerReject();
        $cm = new ConnectionManagerSwappable($wont);

        $promise = $cm->create('www.google.com', 80);
        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);
        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());

        $will = $this->createConnectionManagerMock(true);
        $cm->setConnectionManager($will);

        $promise = $cm->create('www.google.com', 80);
        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);
        $promise->then($this->expectCallableOnce(), $this->expectCallableNever());
    }
}
