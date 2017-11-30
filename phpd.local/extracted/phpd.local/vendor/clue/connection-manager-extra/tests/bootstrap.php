<?php

use React\Promise\Deferred;

require __DIR__ . '/../vendor/autoload.php';

class TestCase extends PHPUnit_Framework_TestCase
{
    protected function expectCallableOnce()
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke');

        return $mock;
    }

    protected function expectCallableNever()
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->never())
            ->method('__invoke');

        return $mock;
    }

    protected function expectCallableOnceParameter($type)
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf($type));

        return $mock;
    }

    protected function expectCallableOnceValue($type)
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf($type));

        return $mock;
    }

    /**
     * @link https://github.com/reactphp/react/blob/master/tests/React/Tests/Socket/TestCase.php (taken from reactphp/react)
     */
    protected function createCallableMock()
    {
        return $this->getMock('CallableStub');
    }

    protected function createConnectionManagerMock($ret)
    {
        $mock = $this->getMockBuilder('React\SocketClient\Connector')
            ->disableOriginalConstructor()
            ->getMock();

        $deferred = new Deferred();
        $deferred->resolve($ret);

        $mock
            ->expects($this->any())
            ->method('create')
            ->will($this->returnValue($deferred->promise()));

        return $mock;
    }

    protected function assertPromiseResolve($promise)
    {
        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $promise->then($this->expectCallableOnce(), $this->expectCallableNever());
    }

    protected function assertPromiseReject($promise)
    {
        $this->assertInstanceOf('React\Promise\PromiseInterface', $promise);

        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
    }
}

class CallableStub
{
    public function __invoke()
    {
    }
}
