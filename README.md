# Bizhost Authentication SDK
For the Symfony Bundle see the package: [gwsn/bizhost-auth](https://gitlab.com/gwsn/packages/bizhost-auth-sdk)

## Installation
You can install the package via composer:

``` bash
composer require gwsn/bizhost-auth-sdk
```

## First configuration to start usage

You need to request a new clientId and clientSecret for the application

1. Go to `bizhost auth portal` https://auth.bizhost.nl/
2. Go to `Applications` https://auth-test.bizhost.nl/admin/clients
3. Go to `Register new application` and follow the wizard.  
   (give it a name like mine is 'example-app-authentication')
5. When created the application is created write down the following details
6. 'Application Identifier', this will be your `$clientId`
7. 'Application Secret', this will be your `$clientSecret`
   (Make sure you write this one down as it will be only shown once)

    Example:
    - Auth meta url: `https://auth.bizhost.nl/.well-known/oauth-authorization-server`


## Basic setup for the Bizhost Authentication SDK 
``` php
use Bizhost\Authentication\Adapter

$authMetaUrl = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
$clientId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
$clientSecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

@todo
```

## Call to fetch AccessToken via Client Credential Flow
``` php

```


## Testing

``` bash
$ composer run-script test
```

## Security

If you discover any security related issues, please email support@bizhost.nl instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
