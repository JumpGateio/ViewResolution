<?php

namespace Tests;

use Orchestra\Testbench\TestCase;

class ResolvesViewsTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        // Make sure we load the view directory.
        $this->app['view']->addLocation(__DIR__ . '/../src/views');

        // Set up our test routes.
        $this->app['router']->get('auto-route', 'Tests\Resources\RouteController@autoRoute');
        $this->app['router']->get('manual-route', 'Tests\Resources\RouteController@manualRoute');
        $this->app['router']->get('manual-layout-route', 'Tests\Resources\RouteController@manualLayoutRoute');
    }

    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \JumpGate\ViewResolution\Providers\ViewServiceProvider::class,
        ];
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The layoutOptions must be an array.
     */
    public function it_errors_when_no_config_is_set()
    {
        (new \Tests\Resources\RouteController)->autoRoute();
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The layoutOptions must have a default layout view.
     */
    public function it_fails_when_no_default_layout_is_present()
    {
        $controller                = new \Tests\Resources\RouteController;
        $controller->layoutOptions = [];

        $controller->autoRoute();
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The layoutOptions must have a ajax layout view.
     */
    public function it_fails_when_no_ajax_layout_is_present()
    {
        $controller                = new \Tests\Resources\RouteController;
        $controller->layoutOptions = [
            'default' => 'test',
        ];

        $controller->autoRoute();
    }

    /**
     * @test
     */
    public function it_can_use_the_config_for_defaults()
    {
        // Use the default layout.
        $this->loadLayout();

        // Call the route and get the auto view debug information.
        $this->call('GET', '/auto-route');

        $debug = viewResolver()->debug();

        // Assert.
        $this->assertEquals('layout', $debug->layout);
    }

    /**
     * @test
     */
    public function it_can_use_the_config_for_default_layout_options()
    {
        // Add the view.
        $this->createView('configDefault');

        // Use the default layout.
        $this->loadLayout();

        // Set up the config entry to specify a view to use.
        $this->app['config']->set('jumpgate.view-resolution.layout_options.default', 'configDefault');

        // Call the route and get the auto view debug information.
        $this->call('GET', '/auto-route');

        $debug = viewResolver()->debug();

        // Assert.
        $this->assertEquals('configDefault', $debug->layout);

        // Clean up.
        $this->deleteView('configDefault');
    }

    /**
     * @test
     */
    public function it_can_automatically_resolve_a_view()
    {
        // Add the view.
        $this->makeViewDir('route');
        $this->createView('route/autoroute');

        // Use the default layout.
        $this->loadLayout();

        // Call the route and get the auto view debug information.
        $this->call('GET', '/auto-route');

        $debug = viewResolver()->debug();

        // Assert.
        $this->assertEquals('route.autoroute', $debug->view);
        $this->assertEquals('auto', $debug->type);

        // Clean up.
        $this->deleteViewDir('route');
    }

    /**
     * @test
     */
    public function it_can_manually_specify_a_view()
    {
        // Use the default layout.
        $this->loadLayout();

        // Call the route and get the auto view debug information.
        $this->call('GET', '/manual-route');

        $debug = viewResolver()->debug();

        // Assert.
        $this->assertEquals('test', $debug->view);
        $this->assertEquals('manual', $debug->type);
    }

    /**
     * @test
     */
    public function it_can_manually_specify_a_layout()
    {
        // Add the view.
        $this->createView('testLayout');

        // Call the route and get the auto view debug information.
        $this->call('GET', '/manual-layout-route');

        $debug = viewResolver()->debug();

        // Assert.
        $this->assertEquals('test', $debug->view);
        $this->assertEquals('manual', $debug->type);
        $this->assertEquals('testLayout', $debug->layout);

        // Clean up.
        $this->deleteView('testLayout');
    }

    /**
     * @test
     */
    public function it_can_get_a_view_from_the_config()
    {
        // Add the view.
        $this->createView('configView');

        // Use the default layout.
        $this->loadLayout();

        // Set up the config entry to specify a view to use.
        $this->app['config']->set('jumpgate.view-resolution.view_locations.Tests\Resources\RouteController.autoroute', 'configView');

        // Call the route and get the auto view debug information.
        $this->call('GET', '/auto-route');

        $debug = viewResolver()->debug();

        // Asset.
        $this->assertEquals('configView', $debug->view);
        $this->assertEquals('config', $debug->type);

        // Clean up.
        $this->deleteView('configView');
    }

    private function loadLayout($boolean = true)
    {
        $this->app['config']->set('jumpgate.view-resolution.load_layout', $boolean);
    }

    private function createView($view)
    {
        $this->app['files']->put(__DIR__ . '/../src/views/' . $view . '.blade.php', 'something');
    }

    private function deleteView($view)
    {
        $this->app['files']->delete(__DIR__ . '/../src/views/' . $view . '.blade.php');
    }

    private function makeViewDir($directory)
    {
        $this->app['files']->makeDirectory(__DIR__ . '/../src/views/' . $directory);
    }

    private function deleteViewDir($directory)
    {
        $this->app['files']->deleteDirectory(__DIR__ . '/../src/views/' . $directory);
    }
}
