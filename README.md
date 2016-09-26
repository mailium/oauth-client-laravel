# Mailium Oauth Client for Laravel Framework

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

### Publishing Only Configuration
```
artisan vendor:publish --provider="Vendor\Providers\PackageServiceProvider" --tag="config"
```

### Publishing Only Database Migrations
```
artisan vendor:publish --provider="Vendor\Providers\PackageServiceProvider" --tag="migrations"
```

## Running Migrations

```
php artisan migrate
```
## Configuring the Oauth Client

Configure your client_id, client_secret, required scopes and app type on config/mailium-oauth.php file.

