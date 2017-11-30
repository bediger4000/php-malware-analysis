# clue/socks-react - SOCKS client and server [![Build Status](https://travis-ci.org/clue/php-socks-react.svg?branch=master)](https://travis-ci.org/clue/php-socks-react)

Async SOCKS client library to connect to SOCKS4, SOCKS4a and SOCKS5 proxy servers,
as well as a SOCKS server implementation, capable of handling multiple concurrent
connections in a non-blocking fashion.

## Description

The SOCKS protocol family can be used to easily tunnel TCP connections independent
of the actual application level protocol, such as HTTP, SMTP, IMAP, Telnet, etc.

## Quickstart examples

Once [installed](#install), initialize a connection to a remote SOCKS proxy server:

```PHP
<?php
include_once __DIR__.'/vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

// create SOCKS client which communicates with SOCKS server 127.0.0.1:9050
$client = new Clue\React\Socks\Client($loop, '127.0.0.1', 9050);

// now work with your $client, see below

$loop->run();
```

### Tunnelled TCP connections

The `Client` uses a [Promise](https://github.com/reactphp/promise)-based interface which makes working with asynchronous functions a breeze.
Let's open up a TCP [Stream](https://github.com/reactphp/stream) connection and write some data:
```PHP
$tcp = $client->createConnector();

$tcp->create('www.google.com',80)->then(function (React\Stream\Stream $stream) {
    echo 'connected to www.google.com:80';
    $stream->write("GET / HTTP/1.0\r\n\r\n");
    // ...
});
```

### SSL/TLS encrypted

If you want to connect to arbitrary SSL/TLS servers, there sure too is an easy to use API available:
```PHP
$ssl = $client->createSecureConnector();

// now create an SSL encrypted connection (notice the $ssl instead of $tcp)
$ssl->create('www.google.com',443)->then(function (React\Stream\Stream $stream) {
    // proceed with just the plain text data
    // everything is encrypted/decrypted automatically
    echo 'connected to SSL encrypted www.google.com';
    $stream->write("GET / HTTP/1.0\r\n\r\n");
    // ...
});
```

### HTTP requests

HTTP operates on a higher layer than this low-level SOCKS implementation.
If you want to issue HTTP requests, you can add a dependency for
[clue/buzz-react](https://github.com/clue/php-buzz-react).
It can interact with this library by issuing all
[http requests through a SOCKS server](https://github.com/clue/php-buzz-react/#via-socks-server).
This works for both plain HTTP and SSL encrypted HTTPS requests.

## SOCKS Protocol versions & differences

While SOCKS4 already had (a somewhat limited) support for `SOCKS BIND` requests
and SOCKS5 added generic UDP support (`SOCKS UDPASSOCIATE`), this library
focuses on the most commonly used core feature of `SOCKS CONNECT`.
In this mode, a SOCKS server acts as a generic proxy allowing higher level
application protocols to work through it.

<table>
  <tr>
    <th></th>
    <th>SOCKS4</th>
    <th>SOCKS4a</th>
    <th>SOCKS5</th>
  </tr>
  <tr>
    <th>Protocol specification</th>
    <td><a href="http://ftp.icm.edu.pl/packages/socks/socks4/SOCKS4.protocol">SOCKS4.protocol</a></td>
    <td><a href="http://ftp.icm.edu.pl/packages/socks/socks4/SOCKS4A.protocol">SOCKS4A.protocol</a></td>
    <td><a href="http://tools.ietf.org/html/rfc1928">RFC 1928</a></td>
  </tr>
  <tr>
    <th>Tunnel outgoing TCP connections</th>
    <td>✓</td>
    <td>✓</td>
    <td>✓</td>
  </tr>
  <tr>
    <th><a href="#remote-vs-local-dns-resolving">Remote DNS resolving</a></th>
    <td>✗</td>
    <td>✓</td>
    <td>✓</td>
  </tr>
  <tr>
    <th>IPv6 addresses</th>
    <td>✗</td>
    <td>✗</td>
    <td>✓</td>
  </tr>
  <tr>
    <th><a href="#username--password-authentication">Username/Password authentication</a></th>
    <td>✗</td>
    <td>✗</td>
    <td>✓ (as per <a href="http://tools.ietf.org/html/rfc1929">RFC 1929</a>)</td>
  </tr>
  <tr>
    <th>Handshake # roundtrips</th>
    <td>1</td>
    <td>1</td>
    <td>2 (3 with authentication)</td>
  </tr>
  <tr>
    <th>Handshake traffic<br />+ remote DNS</th>
    <td>17 bytes<br />✗</td>
    <td>17 bytes<br />+ hostname + 1</td>
    <td><em>variable</em> (+ auth + IPv6)<br />+ hostname - 3</td>
  </tr>
</table>

Note, this is __not__ a full SOCKS5 implementation due to missing GSSAPI
authentication (but it's unlikely you're going to miss it anyway).

### Explicitly setting protocol version

This library supports the SOCKS4, SOCKS4a and SOCKS5 protocol versions.
Usually, there's no need to worry about which protocol version is being used.
Depending on which features you use (e.g. [remote DNS resolving](#remote-vs-local-dns-resolving)
and [authentication](#username--password-authentication)),
the `Client` automatically uses the _best_ protocol available.
In general this library automatically switches to higher protocol versions
when needed, but tries to keep things simple otherwise and sticks to lower
protocol versions when possible.
The `Server` supports all protocol versions by default.

If want to explicitly set the protocol version, use the supported values `4`, `4a` or `5`:

```PHP
// valid protocol versions:
$client->setProtocolVersion('4a');
$server->setProtocolVersion(5);
```

In order to reset the protocol version to its default (i.e. automatic detection),
use `null` as protocol version.

```PHP
$client->setProcolVersion(null);
$server->setProtocolVersion(null);
```

### Remote vs. local DNS resolving

By default, the `Client` uses local DNS resolving to resolve target hostnames
into IP addresses and only transmits the resulting target IP to the socks server.

Resolving locally usually results in better performance as for each outgoing
request both resolving the hostname and initializing the connection to the
SOCKS server can be done simultanously. So by the time the SOCKS connection is
established (requires a TCP handshake for each connection), the target hostname
will likely already be resolved ( _usually_ either already cached or requires a
simple DNS query via UDP).

You may want to switch to remote DNS resolving if your local `Client` either can not
resolve target hostnames because it has no direct access to the internet or if
it should not resolve target hostnames because its outgoing DNS traffic might
be intercepted (in particular when using the
[Tor network](#using-the-tor-anonymity-network-to-tunnel-socks-connections)). 

Local DNS resolving is available in all SOCKS protocol versions.
Remote DNS resolving is only available for SOCKS4a and SOCKS5
(i.e. it is NOT available for SOCKS4).

Valid values are boolean `true`(default) or `false`.

```PHP
$client->setResolveLocal(false);
```

### Username / Password authentication

This library supports username/password authentication for SOCKS5 servers as
defined in [RFC 1929](http://tools.ietf.org/html/rfc1929).

On the client side, simply set your username and password to use for
authentication (see below).
For each further connection the client will merely send a flag to the server
indicating authentication information is available.
Only if the server requests authentication during the initial handshake,
the actual authentication credentials will be transmitted to the server.

Note that the password is transmitted in cleartext to the SOCKS proxy server,
so this methods should not be used on a network where you have to worry about eavesdropping.
Authentication is only supported by protocol version 5 (SOCKS5),
so setting authentication on the `Client` enforces communication with protocol
version 5 and complains if you have explicitly set anything else. 

```PHP
$client->setAuth('username', 'password');
```

Setting authentication on the `Server` enforces each further connected client
to use protocol version 5.
If a client tries to use any other protocol version, does not send along
authentication details or if authentication details can not be verified,
the connection will be rejected.

Because your authentication mechanism might take some time to actually check
the provided authentication credentials (like querying a remote database or webservice),
the server side uses a [Promise](https://github.com/reactphp/promise) based interface.
While this might seem complex at first, it actually provides a very simple way
to handle simultanous connections in a non-blocking fashion and increases overall performance.

```PHP
$server->setAuth(function ($username, $password) {
    // either return a boolean success value right away
    // or use promises for delayed authentication
});
```

Or if you only accept static authentication details, you can use the simple
array-based authentication method as a shortcut:

```PHP
$server->setAuthArray(array(
    'tom' => 'password',
    'admin' => 'root'
));
```

If you do not want to use authentication anymore:

```PHP
$client->unsetAuth();
$server->unsetAuth();
```

## Usage

### Using SSH as a SOCKS server

If you already have an SSH server set up, you can easily use it as a SOCKS
tunnel end point. On your client, simply start your SSH client and use
the `-D [port]` option to start a local SOCKS server (quoting the man page:
a `local "dynamic" application-level port forwarding`) by issuing:

`$ ssh -D 9050 ssh-server`

```PHP
$client = new Client($loop, '127.0.0.1', 9050);
```

### Using the Tor (anonymity network) to tunnel SOCKS connections

The [Tor anonymity network](http://www.torproject.org) client software is designed
to encrypt your traffic and route it over a network of several nodes to conceal its origin.
It presents a SOCKS4 and SOCKS5 interface on TCP port 9050 by default
which allows you to tunnel any traffic through the anonymity network.
In most scenarios you probably don't want your client to resolve the target hostnames,
because you would leak DNS information to anybody observing your local traffic.
Also, Tor provides hidden services through an `.onion` pseudo top-level domain
which have to be resolved by Tor.

```PHP
$client = new Client($loop, '127.0.0.1', 9050);
$client->setResolveLocal(false);
```

## Install

The recommended way to install this library is [through composer](http://getcomposer.org). [New to composer?](http://getcomposer.org/doc/00-intro.md)

```JSON
{
    "require": {
        "clue/socks-react": "~0.2.0"
    }
}
```

## License

MIT, see LICENSE
