![CI](https://github.com/ComplyCube/complycube-php/workflows/CI/badge.svg) ![coverage](https://codecov.io/gh/ComplyCube/complycube-php/branch/main/graph/badge.svg)


# ComplyCube PHP Library

The official PHP library for integrating with the ComplyCube API.

Get started with our [API integration docs](https://docs.complycube.com/api-reference/integration) and check out our full [API reference](https://docs.complycube.com/api-reference/).

## Requirements

PHP 7.4.

## Composer Install

``` bash
$ composer require complycube/complycube
```

Use composers generated loader.
``` php
require_once __DIR__ . '/vendor/autoload.php'; 
```

## Usage

Initialise the ComplyCubeClient with the API key from your [developer dashboard.](https://portal.complycube.com/developers)


``` php
use ComplyCube\ComplyCubeClient;
$complycube = new ComplyCubeClient($apiKey);
```

Create a new client and complete a check

``` php
$newclient = $complycube->clients()->create([
    'type' => 'person',
    'email' => 'john@doe.com',
    'personDetails' => ['firstName' => 'John',
                        'lastName' => 'Smith']]);

$result = $complycube->checks()->create($newclient->id,
                                        ['type' => 'extensive_screening_check']);
```

## Webhooks

ComplyCube uses webhooks to notify your application when an event happens in your account. 

You can use the EventVerifier to validate the messages sent to your application.

``` php
$verifier = new \ComplyCube\EventVerifier('WEBHOOK_SECRET');

$event = $verifier->constructEvent($data, $headers[SIGNATURE_KEY]);
```

Check out the [Webhooks guide](https://docs.complycube.com/documentation/guides/webhooks)

### Integration Checklist

When you’re done developing your ComplyCube integration and you’re ready to go live, refer to this [checklist](https://docs.complycube.com) to ensure you have covered all critical steps.

## About ComplyCube

[ComplyCube](https://www.complycube.com/en), the award-winning SaaS & API platform, specializes in innovative solutions for Identity Verification (IDV), Anti-Money Laundering (AML), and Know Your Customer (KYC) compliance. With a vast clientele across multiple industries such as financial services, transport, healthcare, e-commerce, cryptocurrency, FinTech, and telecoms, ComplyCube is a leading entity in the IDV domain.
This ISO-certified platform is distinguished by its ability to integrate quickly across channels and the comprehensive nature of its service suite, including Low/No-Code solutions, extensive API, Mobile SDKs, Client Libraries, and seamless CRM integrations.
