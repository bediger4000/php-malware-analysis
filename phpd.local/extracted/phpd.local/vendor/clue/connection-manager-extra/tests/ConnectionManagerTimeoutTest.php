<?php

use ConnectionManager\Extra\ConnectionManagerReject;

use React\Stream\Stream;

use ConnectionManager\Extra\ConnectionManagerDelay;

use ConnectionManager\Extra\ConnectionManagerTimeout;

class ConnectionManagerTimeoutTest extends TestCase
{
    public function setUp()
    {
        $this->loop = React\EventLoop\Factory::create();
    }

    public function testTimeoutOkay()
    {
        $will = $this->createConnectionManagerMock(true);
        $cm = new ConnectionManagerTimeout($will, $this->loop, 0.1);

        $promise = $cm->create('www.google.com', 80);
        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $this->loop->run();
        $promise->then($this->expectCallableOnce(), $this->expectCallableNever());
    }

    public function testTimeoutExpire()
    {
        $will = $this->createConnectionManagerMock(new Stream(fopen('php://temp', 'r'), $this->loop));
        $wont = new ConnectionManagerDelay($will, $this->loop, 0.2);

        $cm = new ConnectionManagerTimeout($wont, $this->loop, 0.1);

        $promise = $cm->create('www.google.com', 80);
        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $this->loop->run();
        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
    }

    public function testTimeoutAbort()
    {
        $wont = new ConnectionManagerReject();

        $cm = new ConnectionManagerTimeout($wont, $this->loop, 0.1);

        $promise = $cm->create('www.google.com', 80);
        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $this->loop->run();
        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
    }
}
