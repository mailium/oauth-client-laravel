<?php namespace MailiumOauthClient\MailiumOauthClientLaravel;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use MailiumOauthClient\MailiumOauthClient;


class MailiumOauthClientServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            // Publish configuration
            $configPath = __DIR__ . '/../../../config/mailium-oauth.php';
            $this->publishes([
                $configPath => config_path('mailium-oauth.php'),
            ], 'config');

            // Publish migrations
            $migrationPath = __DIR__ . '/../../../database/migrations/';
            $this->publishes([
                $migrationPath => database_path('migrations'),
            ], ' migrations');
        }

        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/../../../http/routes.php';
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MailiumOauthClient::class, function ($app) {
            $oauthClient = new MailiumOauthClient(null, true);
            $oauthClient->setClientID(config('mailium-oauth.client_id'));
            $oauthClient->setClientSecret(config('mailium-oauth.client_secret'));
            $oauthClient->setScopes(config('mailium-oauth.required_scopes'));
            $oauthClient->setTokenStoreCallbackFunction(config('mailium-oauth.model') . '::saveOauthToken');
            $oauthClient->setRedirectUri(config('mailium-oauth.redirect_uri'));
            $oauthClient->setAppType(config('mailium-oauth.app_type'));

            return $oauthClient;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array("mailiumoauthclient");
    }

    protected function setupConfig()
    {

    }
}
