<?php

namespace Insight\Portal\Connections;

use Illuminate\Support\ServiceProvider;

class RestClientServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Insight\Portal\Connections\PortalRestClient', function()
        {
           return new PortalRestClient(getenv('REST_CONSUMER_KEY'), getenv('REST_CONSUMER_SECRET'), getenv('REST_TOKEN'), getenv('REST_TOKEN_SECRET'), getenv('REST_BASE_URL'));
        });
    }
}
