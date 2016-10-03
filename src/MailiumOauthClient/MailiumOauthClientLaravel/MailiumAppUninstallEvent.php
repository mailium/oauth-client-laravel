<?php

namespace MailiumOauthClient\MailiumOauthClientLaravel;

class MailiumAppUninstallEvent
{
    /**
     * @var MailiumAppAuthenticatable the account that the app is uninstalled from
     */
    public $account;

    /**
     * MailiumAppUninstallEvent constructor.
     * @param MailiumAppAuthenticatable $account
     */
    public function __construct($account)
    {
        $this->account = $account;
    }
}
