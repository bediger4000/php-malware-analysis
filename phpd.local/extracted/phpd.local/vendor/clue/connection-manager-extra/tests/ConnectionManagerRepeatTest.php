<?php

use ConnectionManager\Extra\ConnectionManagerRepeat;
use ConnectionManager\Extra\ConnectionManagerReject;

class ConnectionManagerRepeatTest extends TestCase
{
    public function testRepeatRejected()
    {
        $wont = new ConnectionManagerReject();
        $cm = new ConnectionManagerRepeat($wont, 3);
        $promise = $cm->create('www.google.com', 80);

        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidRepetitions()
    {
        $wont = new ConnectionManagerReject();
        $cm = new ConnectionManagerRepeat($wont, -3);
    }
}
