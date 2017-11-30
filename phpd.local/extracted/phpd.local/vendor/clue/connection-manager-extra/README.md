# clue/connection-manager-extra [![Build Status](https://travis-ci.org/clue/php-connection-manager-extra.svg?branch=master)](https://travis-ci.org/clue/php-connection-manager-extra)

This project provides _extra_ (in terms of "additional", "extraordinary", "special" and "unusual") decorators
built upon [react/socket-client](https://github.com/reactphp/socket-client).

## Introduction

If you're not already familar with [react/socket-client](https://github.com/reactphp/socket-client),
think of it as an async (non-blocking) version of [`fsockopen()`](http://php.net/manual/en/function.fsockopen.php)
or [`stream_socket_client()`](http://php.net/manual/en/function.stream-socket-client.php).
I.e. before you can send and receive data to/from a remote server, you first have to establish a connection - which
takes its time because it involves several steps.
In order to be able to establish several connections at the same time, [react/socket-client](https://github.com/reactphp/socket-client) provides a simple
API to establish simple connections in an async (non-blocking) way.

This project includes several classes that extend this base functionality by implementing the same simple `ConnectorInterface`.
This interface provides a single promise-based method `create($host, $ip)` which can be used to easily notify
when the connection is successfully established or the `Connector` gives up and the connection fails.

```php
$connector->create('www.google.com', 80)->then(function ($stream) {
    echo 'connection successfully established';
    $stream->write("GET / HTTP/1.0\r\nHost: www.google.com\r\n\r\n");
    $stream->end();
}, function ($exception) {
    echo 'connection attempt failed: ' . $exception->getMessage();
});

```

Because everything uses the same simple API, the resulting `Connector` classes can be easily interchanged
and be used in places that expect the normal `ConnectorInterface`. This can be used to stack them into each other,
like using [timeouts](#timeout) for TCP connections, [delaying](#delay) SSL/TLS connections,
[retrying](#repeating--retrying) failed connection attemps, [randomly](#random) picking a `Connector` or
any combination thereof.

## Usage

This section lists all this libraries' features along with some examples.
The examples assume you've [installed](#install) this library and
already [set up a `SocketClient/Connector` instance `$connector`](https://github.com/reactphp/socket-client#async-tcpip-connections).

All classes are located in the `ConnectionManager\Extra` namespace.

### Repeat

The `ConnectionManagerRepeat($connector, $repeat)` retries connecting to the given location up to a maximum
of `$repeat` times when the connection fails.

```php
$connectorRepeater = new \ConnectionManager\Extra\ConnectionManagerRepeat($connector, 3);
$connectorRepeater->create('www.google.com', 80)->then(function ($stream) {
    echo 'connection successfully established';
    $stream->close();
});
```

### Timeout

The `ConnectionManagerTimeout($connector, $timeout)` sets a maximum `$timeout` in seconds on when to give up
waiting for the connection to complete.

### Delay

The `ConnectionManagerDelay($connector, $delay)` sets a fixed initial `$delay` in seconds before actually
trying to connect. (Not to be confused with [`ConnectionManagerTimeout`](#timeout) which sets a _maximum timeout_.)

### Reject

The `ConnectionManagerReject()` simply rejects every single connection attempt.
This is particularly useful for the below [`ConnectionManagerSelective`](#selective) to reject connection attempts
to only certain destinations (for example blocking advertisements or harmful sites).

### Swappable

The `ConnectionManagerSwappable($connector)` is a simple decorator for other `ConnectionManager`s to
simplify exchanging the actual `ConnectionManager` during runtime (`->setConnectionManager($connector)`).

### Consecutive

The `ConnectionManagerConsecutive($connectors)` establishs connections by trying to connect through
any of the given `ConnectionManager`s in consecutive order until the first one succeeds.

### Random

The `ConnectionManagerRandom($connectors)` works much like `ConnectionManagerConsecutive` but instead
of using a fixed order, it always uses a randomly shuffled order.

### Selective

The `ConnectionManagerSelective()` manages several `Connector`s and forwards connection through either of
those besed on lists similar to to firewall or networking access control lists (ACLs).

This allows fine-grained control on how to handle outgoing connections, like rejecting advertisements,
delaying HTTP requests, or forwarding HTTPS connection through a foreign country.

```php
$connectorSelective->addConnectionManagerFor($connector, $targetHost, $targetPort, $priority);
```

## Install

The recommended way to install this library is [through composer](http://getcomposer.org). [New to composer?](http://getcomposer.org/doc/00-intro.md)

```JSON
{
    "require": {
        "clue/connection-manager-extra": "0.3.*"
    }
}
```

## License

MIT
