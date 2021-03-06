<?php

namespace JumpGate\ViewResolution\Providers;

use Illuminate\Support\ServiceProvider;
use JumpGate\ViewResolution\Builders\Inertia;
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
        $this->app->singleton('viewResolver', function ($app) {
            return $app->make(View::class);
        });
        $this->app->singleton('inertiaResolver', function ($app) {
            return $app->make(Inertia::class);
        });

        if (checkDebugbar()) {
            $debugbar = $this->app['debugbar'];

            if ($debugbar->shouldCollect('auto_views')) {
                $debugbar->addCollector(new AutoViewCollector());
            }
        }
    }

    public function boot()
    {
        $this->loadConfigs();
        $this->loadViews();
    }

    /**
     * Allow configs to be published but use the included by default.
     */
    protected function loadConfigs()
    {
        $this->publishes([
            __DIR__ . '/../../../config/view-resolution.php' => config_path('jumpgate/view-resolution.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/view-resolution.php', 'jumpgate.view-resolution'
        );
    }

    /**
     * Register views
     *
     * @return void
     */
    protected function loadViews()
    {
        if ($this->app['config']->get('jumpgate.view-resolution.load_layout')) {
            $this->app['view']->addLocation(__DIR__ . '/../../../views');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['viewResolver'];
    }
}
