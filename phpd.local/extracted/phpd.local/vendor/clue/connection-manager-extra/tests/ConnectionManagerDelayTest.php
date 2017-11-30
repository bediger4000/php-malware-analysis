<?php


use ConnectionManager\Extra\ConnectionManagerDelay;

class ConnectionManagerDelayTest extends TestCase
{
    public function setUp()
    {
        $this->loop = React\EventLoop\Factory::create();
    }

    public function testDelayTenth()
    {
        $will = $this->createConnectionManagerMock(true);
        $cm = new ConnectionManagerDelay($will, $this->loop, 0.1);

        $promise = $cm->create('www.google.com', 80);
        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $this->loop->run();
        $promise->then($this->expectCallableOnce(), $this->expectCallableNever());
    }
}
