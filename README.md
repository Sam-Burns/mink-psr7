[![Build Status](https://travis-ci.org/Sam-Burns/mink-psr7.svg?branch=master)](https://travis-ci.org/Sam-Burns/mink-psr7)

# Mink PSR-7

This is a PSR-7 adapter for Mink drivers, for use with Behat.  It is a work in progress.  Pull requests welcome.

## Use

```php
$driver = new \Behat\Mink\Driver\GoutteDriver(); // or any kind of Mink driver
$psr7MinkAdapter = new \SamBurns\MinkPsr7\Psr7Adapter($driver);
$response = $psr7MinkDriver->doRequest($request);
// ^ taking Psr\Http\Message\ServerRequestInterface, returning ...\ResponseInterface.

```

## References
- [Mink](https://github.com/minkphp/Mink)
- [PSR-7](http://www.php-fig.org/psr/psr-7/)

## Contributions

All contributions welcome.  After cloning the repository and running Composer, you can run the tests like this:

```ssh
./tests/run_tests.sh
```

It will spin up a real webserver with `php -S`.  PHPUnit will then run, sending requests to the server and checking the request headers etc. that were received.
