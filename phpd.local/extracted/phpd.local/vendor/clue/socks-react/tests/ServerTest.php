<?php

use Clue\React\Socks\Server;

class ServerTest extends TestCase
{
    /** @var Server */
    private $server;

    public function setUp()
    {
        $socket = $this->getMockBuilder('React\Socket\Server')
            ->disableOriginalConstructor()
            ->getMock();

        $loop = $this->getMockBuilder('React\EventLoop\StreamSelectLoop')
            ->disableOriginalConstructor()
            ->getMock();

        $connector = $this->getMockBuilder('React\SocketClient\Connector')
            ->disableOriginalConstructor()
            ->getMock();

        $this->server = new Server($loop, $socket, $connector);
    }

    public function testSetProtocolVersion()
    {
        $this->server->setProtocolVersion(4);
        $this->server->setProtocolVersion('4a');
        $this->server->setProtocolVersion(5);
        $this->server->setProtocolVersion(null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidProtocolVersion()
    {
        $this->server->setProtocolVersion(6);
    }

    public function testSetAuthArray()
    {
        $this->server->setAuthArray(array());

        $this->server->setAuthArray(array(
            'name1' => 'password1',
            'name2' => 'password2'
        ));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetAuthInvalid()
    {
        $this->server->setAuth(true);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testUnableToSetAuthIfProtocolDoesNotSupportAuth()
    {
        $this->server->setProtocolVersion(4);

        $this->server->setAuthArray(array());
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testUnableToSetProtocolWhichDoesNotSupportAuth()
    {
        $this->server->setAuthArray(array());

        // this is okay
        $this->server->setProtocolVersion(5);

        $this->server->setProtocolVersion(4);
    }

    public function testUnsetAuth()
    {
        $this->server->unsetAuth();
        $this->server->unsetAuth();
    }
}
