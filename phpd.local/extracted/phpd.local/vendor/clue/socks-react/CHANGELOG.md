# CHANGELOG

This file is a manually maintained list of changes for each release. Feel free
to add your changes here when sending pull requests. Also send corrections if
you spot any mistakes.

## 0.2.0 (2014-09-27)

* BC break: Simplify constructors my making parameters optional.
  ([#10](https://github.com/clue/php-socks-react/pull/10))
  
  The `Factory` has been removed, you can now create instances of the `Client`
  and `Server` yourself:
  
  ```php
  // old
  $factory = new Factory($loop, $dns);
  $client = $factory->createClient('localhost', 9050);
  $server = $factory->createSever($socket);
  
  // new
  $client = new Client($loop, 'localhost', 9050);
  $server = new Server($loop, $socket);
  ```

* BC break: Remove HTTP support and link to [clue/buzz-react](https://github.com/clue/php-buzz-react) instead.
  ([#9](https://github.com/clue/php-socks-react/pull/9))
  
  HTTP operates on a different layer than this low-level SOCKS library.
  Removing this reduces the footprint of this library.
  
  > Upgrading? Check the [README](https://github.com/clue/php-socks-react#http-requests) for details.  

* Fix: Refactored to support other, faster loops (libev/libevent)
  ([#12](https://github.com/clue/php-socks-react/pull/12))

* Explicitly list dependencies, clean up examples and extend test suite significantly

## 0.1.0 (2014-05-19)

* First stable release
* Async SOCKS `Client` and `Server` implementation
* Project was originally part of [clue/socks](https://github.com/clue/php-socks)
  and was split off from its latest releave v0.4.0
  ([#1](https://github.com/clue/reactphp-socks/issues/1))

> Upgrading from clue/socks v0.4.0? Use namespace `Clue\React\Socks` instead of `Socks` and you're ready to go!

## 0.0.0 (2011-04-26)

* Initial concept, originally tracked as part of
  [clue/socks](https://github.com/clue/php-socks)
