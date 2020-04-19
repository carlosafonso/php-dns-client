# php-dns-client

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

A DNS client library purely implemented in PHP, compatible with PHP 7.2+.

**This is a work in progress.** These are the Resource Record types currently understood by the library:

* A
* AAAA
* CNAME
* NS
* SRV
* MX
* SOA
* PTR

Processing responses with other types of Resource Records currently will result in an exception.

## Install

Via Composer:

``` bash
$ composer require carlosafonso/php-dns-client
```

## Usage

The `Client` class it the main entry point to consume the library. `Client` instances allow to send `Request` objects to a name server for resolution using the `query()` method:

``` php
require __DIR__ . '/vendor/autoload.php';

$client = new Afonso\Dns\Client();

// Let's make an A request for example.com
$request = new Afonso\Dns\Request('google.com', Afonso\Dns\Request::RR_TYPE_A);

// Let's send it to name server 8.8.8.8
$response = $client->query($request, '8.8.8.8');

$response->getResourceRecords()[0]->getValue(); // 216.58.211.238
```

## Security

If you discover any security related issues, please email the author directly instead of using the issue tracker.

[ico-version]: https://img.shields.io/packagist/v/carlosafonso/php-dns-client.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/carlosafonso/php-dns-client/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/carlosafonso/php-dns-client.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/carlosafonso/php-dns-client.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/carlosafonso/php-dns-client.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/carlosafonso/php-dns-client
[link-travis]: https://travis-ci.org/carlosafonso/php-dns-client
[link-scrutinizer]: https://scrutinizer-ci.com/g/carlosafonso/php-dns-client/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/carlosafonso/php-dns-client
[link-downloads]: https://packagist.org/packages/carlosafonso/php-dns-client
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
