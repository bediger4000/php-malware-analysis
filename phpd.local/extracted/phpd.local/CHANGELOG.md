# CHANGELOG

This file is a manually maintained list of changes for each release. Feel free
to add your changes here when sending pull requests. Also send corrections if
you spot any mistakes.

## 0.3.4 (2014-10-22)

* Fix: Downgrade broken dependency for react/socket-client to v0.3.1
  (#16)

## 0.3.3 (2014-09-27)

* Replace [clue/socks](https://github.com/clue/php-socks) with
  [clue/socks-react](https://github.com/clue/php-socks-react)
  and fix support for faster loops (libev / libevent)
  (#13 and #14)
  
* Fix broken dependencies by updating to their latest versions
  (#15)

## 0.3.2 (2014-04-05)

* Fix: Fixed invalid reference in the `ping` command.

## 0.3.1 (2013-06-24)

* Fix: Invalid bin path (unable to run psocksd.phar)

## 0.3.0 (2013-06-24)

* Fix: Support PHP < 5.3.6
* Update clue/socks to v0.4 and clue/connection-manager-extra to v0.3.0 and 
react to v0.3.0 (#1)

## 0.2.0 (2013-06-11)

* Feature: Interactive CLI commands
* Feature: Connections can be forwarded selectively to next SOCKS proxy
* Feature: Event log with authentication, traffic and times

## 0.1.0 (2012-12-23)

* First tagged release

