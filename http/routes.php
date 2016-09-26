<?php

use Illuminate\Http\Request;
use MailiumOauthClient\MailiumOauthClientLaravel\MailiumAppUninstallEvent;
use MailiumOauthClient\MailiumOauthClientLaravel\MailiumAppUser;
use MailiumOauthClient\MailiumOauthClientLaravel\MailiumOauthClientMiddleware;

Route::post('/uninstall', ['middleware' => [
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \MailiumOauthClient\MailiumOauthClientLaravel\MailiumOauthClientMiddleware::class
], function(Request $request) {
    // Get current user from the request
    /** @var MailiumAppUser $user */
    $user = $request->attributes->get('mailium_app_user');

    // Delete user from database;
    $user->delete();

    // Fire uninstall event
    Event::fire(new MailiumAppUninstallEvent($user->accid));

    return response()->json([], 200);
}])->name('mailium_app_uninstall');
