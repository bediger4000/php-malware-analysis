<?php

use React\Stream\Stream;

use ConnectionManager\Extra\Multiple\ConnectionManagerSelective;
use ConnectionManager\Extra\ConnectionManagerReject;

class ConnectionManagerSelectiveTest extends TestCase
{
    public function testEmptyWillAlwaysReject()
    {
        $cm = new ConnectionManagerSelective();

        $promise = $cm->create('www.google.com', 80);
        $this->assertPromiseReject($promise);
    }

    public function testReject()
    {
        $wont = new ConnectionManagerReject();
        $will = $this->createConnectionManagerMock(new Stream(fopen('php://temp', 'r'), $this->createLoopMock()));

        $cm = new ConnectionManagerSelective();

        $cm->addConnectionManagerFor($will, 'www.google.com', 443);
        $cm->addConnectionManagerFor($will, 'www.youtube.com');

        $this->assertPromiseResolve($cm->create('www.google.com', 443));

        $this->assertPromiseReject($cm->create('www.google.com', 80));

        $this->assertPromiseResolve($cm->create('www.youtube.com', 80));
    }

    public function testRemove()
    {
        $wont = new ConnectionManagerReject();

        $cm = new ConnectionManagerSelective();
        $this->assertCount(0, $cm->getConnectionManagerEntries());

        $id = $cm->addConnectionManagerFor($wont);
        $this->assertCount(1, $cm->getConnectionManagerEntries());

        $cm->removeConnectionManagerEntry($id);
        $this->assertCount(0, $cm->getConnectionManagerEntries());

        // removing a non-existant ID is a NO-OP
        $cm->removeConnectionManagerEntry(12345);
        $this->assertCount(0, $cm->getConnectionManagerEntries());
    }

    public function testSamePriorityFirstWins()
    {
        $wont = new ConnectionManagerReject();
        $will = $this->createConnectionManagerMock(new Stream(fopen('php://temp', 'r'), $this->createLoopMock()));

        $cm = new ConnectionManagerSelective();

        $cm->addConnectionManagerFor($will, 'www.google.com', 443, 100);
        $cm->addConnectionManagerFor($wont, ConnectionManagerSelective::MATCH_ALL, ConnectionManagerSelective::MATCH_ALL, 100);

        $this->assertPromiseResolve($cm->create('www.google.com', 443));
        $this->assertPromiseReject($cm->create('www.google.com', 80));
    }

    public function testWildcardsMatch()
    {
        $wont = new ConnectionManagerReject();
        $will = $this->createConnectionManagerMock(new Stream(fopen('php://temp', 'r'), $this->createLoopMock()));

        $cm = new ConnectionManagerSelective();

        $cm->addConnectionManagerFor($will, '*.com');
        $cm->addConnectionManagerFor($will, '*', '443-444,8080');
        $cm->addConnectionManagerFor($will, '*.youtube.*,youtube.*', '*');

        $this->assertPromiseResolve($cm->create('www.google.com', 80));
        $this->assertPromiseReject($cm->create('www.google.de', 80));

        $this->assertPromiseResolve($cm->create('www.google.de', 443));
        $this->assertPromiseResolve($cm->create('www.google.de', 444));
        $this->assertPromiseResolve($cm->create('www.google.de', 8080));
        $this->assertPromiseReject($cm->create('www.google.de', 445));

        $this->assertPromiseResolve($cm->create('www.youtube.de', 80));
        $this->assertPromiseResolve($cm->create('download.youtube.de', 80));
        $this->assertPromiseResolve($cm->create('youtube.de', 80));
    }

    private function createLoopMock()
    {
        return $this->getMockBuilder('React\EventLoop\StreamSelectLoop')
                     ->disableOriginalConstructor()
                     ->getMock();
    }
}
