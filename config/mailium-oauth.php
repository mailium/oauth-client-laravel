<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application credentials
    |--------------------------------------------------------------------------
    | App credentails
    */

    'app_id' => env('MAILIUM_APP_ID', ''),
    'client_id' => env('MAILIUM_APP_CLIENT_ID', ''),
    'client_secret' => env('MAILIUM_APP_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Required scopes
    |--------------------------------------------------------------------------
    | Scopes that application needs, list the scopes separated by space , e.g. 'account.read list.read'
    */
    'required_scopes' => 'account.read',

    /*
    |--------------------------------------------------------------------------
    | Redirect URI
    |--------------------------------------------------------------------------
    | Application's URI
    */
    'redirect_uri' => env('MAILIUM_APP_REDIRECT_URI', ''),

    /*
    |--------------------------------------------------------------------------
    | Application type (embedded or standalone)
    |--------------------------------------------------------------------------
    |
    */
    'app_type' => env('MAILIUM_APP_TYPE', 'embedded'),

    /*
    |--------------------------------------------------------------------------
    | Eloquent model
    |--------------------------------------------------------------------------
    |
    */
    'model' => \MailiumOauthClient\MailiumOauthClientLaravel\MailiumAppAuthenticatable::class,
];
