# Mailium Oauth Client for Laravel Framework

[![Latest Stable Version](https://poser.pugx.org/mailium/oauth-client-laravel/v/stable.svg)](https://packagist.org/packages/mailium/oauth-client-laravel) [![Monthly Downloads](https://poser.pugx.org/mailium/oauth-client-laravel/d/monthly.png)](https://packagist.org/packages/mailium/oauth-client-laravel) [![License](https://poser.pugx.org/mailium/oauth-client-laravel/license.svg)](https://packagist.org/packages/mailium/oauth-client-laravel) ![Build Status](https://travis-ci.org/mailium/oauth-client-laravel.svg?branch=master)


## Installation & Configuration

### Service Provider

Add Mailium Service Provider to providers array in config/app.php

```
MailiumOauthClient\MailiumOauthClientLaravel\MailiumOauthClientServiceProvider::class,
```

### Facade

Add Mailium Facade to aliases array in config/app.php

```
'MailiumOauthClient' => MailiumOauthClient\MailiumOauthClientLaravel\MailiumOauthClientFacade::class,
```

### Middleware

Add Mailium Middleware to the middleware group array in app/Http/Kernel.php

```
\MailiumOauthClient\MailiumOauthClientLaravel\MailiumOauthClientMiddleware::class,
```

Example:

```php
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \MailiumOauthClient\MailiumOauthClientLaravel\MailiumOauthClientMiddleware::class,
        ],
```

## Publishing Migrations and Configuration

```
php artisan vendor:publish
```

## Running Migrations

```
php artisan migrate
```
## Configuring the Oauth Client

Configure your client_id, client_secret, required scopes and app type on config/mailium-oauth.php file.

## Using the client on controllers

Oauth client middleware adds four attributes to the incoming requests

- mailium_app_accid             (account identifier)
- mailium_app_user              (mailium app user object)
- mailium_app_just_installed    (boolean variable defines if the app is just installed and the request is first one )
- mailium_api_client            (API wrapper)

### Getting accid (Account Identifier)

```
      $this->accId = $request->attributes->get('mailium_app_accid');
```

### Getting user (mailium app user)

```
      $this->mailiumAppUser = $request->attributes->get('mailium_app_user');
```

### Indicator of first request to the app

```
      $this->justInstalled = $request->attributes->get('mailium_app_just_installed');
```

### Getting API client

```
      $this->apiClient = $request->attributes->get('mailium_api_client');
```

### Running API commands on controller
```
      $this->apiClient->run('List.GetList',array());
```
