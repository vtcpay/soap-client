# PHP SOAP CLIENT #

A PHP client for the VtcPay SOAP API
==============================================================

Introduction
------------

### Features ###

This library's features include the following.

* Added an EventDispatcher to your Soap client. It will trigger events at all important phases of the SOAP call
* Easily extensible through events: add custom logging, caching, error handling etc
* Completely tested

Installation
------------

This library is available on [Packagist](http://packagist.org/packages/vtcpay/soap-client). 
The recommended way to install this library is through [Composer](http://getcomposer.org):

```bash
$ php composer.phar require vtcpay/soap-client dev-master
```

Usage
-----

### The client ###

First construct a client using the builder:

```php
$builder = new \Vtcpay\SoapClient\ClientBuilder(
  '/path/to/your/vtcpay/wsdl/sandbox.enterprise.wsdl.xml'
);

$client = $builder->build();
```

### Logging ###

To enable logging for the client, call `withLog()` on the builder. For instance when using [Monolog](https://github.com/Seldaek/monolog):

```php
$log = new \Monolog\Logger('vtcpay');  
$log->pushHandler(new \Monolog\Handler\StreamHandler('/path/to/your.log'));

$builder = new \Vtcpay\SoapClient\ClientBuilder(
  '/path/to/your/vtcpay/wsdl/sandbox.enterprise.wsdl.xml'
);
$client = $builder->withLog($log)
  ->build();
```

All requests to the VnPay API, as well as the responses and any errors that it returns, will now be logged.

### Completely tested ###

* [tests/index.php](https://github.com/vtcpay/soap-client/blob/master/tests/index.php)