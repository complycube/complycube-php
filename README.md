![CI](https://github.com/ComplyCube/complycube-php/workflows/CI/badge.svg)

# ComplyCube PHP Library

The official PHP library for integrating with the ComplyCube API.

Check out the [API integration docs](https://docs.complycube.com/api-reference/integration).

Check out the [API reference](https://docs.complycube.com/api-reference/).

## Requirements

PHP 7.4 and later.

## Composer Install

``` bash
$ composer require complycube/complycube
```

Use composers generated loader.
``` php
require_once __DIR__ . '/vendor/autoload.php'; 
```

## Usage

Initialise the ComplyCubeClient with the api key from your [developer dashboard.](https://portal.doccheck.com/developers)


``` php
use ComplyCube\ComplyCubeClient;
$complycube = new ComplyCubeClient($apiKey);
```

Create a new client and complete a standard check

``` php
$newclient = $complycube->clients()->create([
    'type' => 'person',
    'email' => 'john@doe.com',
    'personDetails' => ['firstName' => 'John',
                        'lastName' => 'Smith']]);

$result = $complycube->checks()->create([
    'type' => 'extensive_screening_check',
    'clientId' => $newclient->id]);
```

## Custom requests

Requests are made using Guzzle, so any Guzzle supported options 
can be passed using the $options parameter when making requests

For example to disallow retries when creating checks

```php
$result = $$checksApi->create($newCheck, 
                              [retry_enabled => False]);
```

To set a proxy.

```php
$result = $checksApi->create($newCheck, 
                             [proxy => 'https://myproxy.com']);
```

### More Documentation

More documentation and code examples can be found at [https://docs.complycube.com](https://docs.complycube.com)
