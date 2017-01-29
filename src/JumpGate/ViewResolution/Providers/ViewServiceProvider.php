<?php

namespace JumpGate\ViewResolution\Providers;

use Illuminate\Support\ServiceProvider;
use JumpGate\ViewResolution\Builders\View;
use JumpGate\ViewResolution\Collectors\AutoViewCollector;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('viewBuilder', function ($app) {
            return $app->make(View::class);
        });

        if ($this->app->environment('local') && checkDebugbar()) {
            $debugbar = $this->app['debugbar'];

            if ($debugbar->shouldCollect('auto_views')) {
                $debugbar->addCollector(new AutoViewCollector());
            }
        }
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../config/view-routing.php' => config_path('jumpgate/view-routing.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['viewBuilder'];
    }
}
