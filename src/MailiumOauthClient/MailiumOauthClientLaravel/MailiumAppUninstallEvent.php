<?php

namespace MailiumOauthClient\MailiumOauthClientLaravel;

class MailiumAppUninstallEvent
{
    /**
     * @var string ID of the account that the app is uninstalled from
     */
    public $account;

    /**
     * MailiumAppUninstallEvent constructor.
     * @param string $account
     */
    public function __construct($account)
    {
        $this->account = $account;
    }
}
