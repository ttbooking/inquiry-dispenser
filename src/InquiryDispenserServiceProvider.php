<?php

namespace TTBooking\InquiryDispenser;

use Illuminate\Support\ServiceProvider;

class InquiryDispenserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Factor::setEventDispatcher($this->app['events']);

        $this->publishes([
            __DIR__.'/../config/dispenser.php' => $this->app->configPath('dispenser.php'),
        ], 'dispenser:config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.dispenser.track-table', function ($app) {
            return new Console\TrackTableCommand($app['files'], $app['composer']);
        });

        $this->commands('command.dispenser.track-table');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.dispenser.track-table'];
    }
}
