<?php

use Clue\React\Socks\Client;

class ClientTest extends TestCase
{
    /** @var  Client */
    private $client;

    public function setUp()
    {
        $loop = React\EventLoop\Factory::create();
        $this->client = new Client($loop, '127.0.0.1', 9050);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAuthInformation()
    {
        $this->client->setAuth(str_repeat('a', 256), 'test');
    }

    /**
     * @expectedException UnexpectedValueException
     * @dataProvider providerInvalidAuthVersion
     */
    public function testInvalidAuthVersion($version)
    {
        $this->client->setAuth('username', 'password');
        $this->client->setProtocolVersion($version);
    }

    public function providerInvalidAuthVersion()
    {
        return array(array('4'), array('4a'));
    }

    public function testValidAuthVersion()
    {
        $this->client->setAuth('username', 'password');
        $this->assertNull($this->client->setProtocolVersion(5));
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidCanNotSetAuthenticationForSocks4()
    {
        $this->client->setProtocolVersion(4);
        $this->client->setAuth('username', 'password');
    }

    public function testUnsetAuth()
    {
        // unset auth even if it's not set is valid
        $this->client->unsetAuth();

        $this->client->setAuth('username', 'password');
        $this->client->unsetAuth();
    }

    /**
     * @dataProvider providerValidProtocolVersion
     */
    public function testValidProtocolVersion($version)
    {
        $this->assertNull($this->client->setProtocolVersion($version));
    }

    public function providerValidProtocolVersion()
    {
        return array(array('4'), array('4a'), array('5'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidProtocolVersion()
    {
        $this->client->setProtocolVersion(3);
    }

    public function testValidResolveLocal()
    {
        $this->assertNull($this->client->setResolveLocal(false));
        $this->assertNull($this->client->setResolveLocal(true));
        $this->assertNull($this->client->setProtocolVersion('4'));
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidResolveRemote()
    {
        $this->client->setProtocolVersion('4');
        $this->client->setResolveLocal(false);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidResolveRemoteVersion()
    {
        $this->client->setResolveLocal(false);
        $this->client->setProtocolVersion('4');
    }

    public function testSetTimeout()
    {
        $this->client->setTimeout(1);
        $this->client->setTimeout(2.0);
        $this->client->setTimeout(3);
    }

    public function testCreateConnector()
    {
        $this->assertInstanceOf('\React\SocketClient\ConnectorInterface', $this->client->createConnector());
    }

    public function testCreateSecureConnector()
    {
        $this->assertInstanceOf('\React\SocketClient\SecureConnector', $this->client->createSecureConnector());
    }

    /**
     * @dataProvider providerAddress
     */
    public function testGetConnection($host, $port)
    {
        $this->assertInstanceOf('\React\Promise\PromiseInterface', $this->client->getConnection($host, $port));
    }

    public function providerAddress()
    {
        return array(
            array('localhost','80'),
            array('invalid domain','non-numeric')
        );
    }
}
