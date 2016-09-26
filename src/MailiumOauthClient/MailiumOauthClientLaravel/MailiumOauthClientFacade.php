<?php namespace MailiumOauthClient\MailiumOauthClientLaravel;

use Illuminate\Support\Facades\Facade;

class MailiumOauthClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mailiumoauthclient';
    }
}
