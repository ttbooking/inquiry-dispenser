<?php

namespace TTBooking\InquiryDispenser;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\Event;

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
        Factor::observe(Observers\TrackFactorState::class);

        if ($this->app->runningInConsole()) {

            $this->app->booted(function () {

                /** @var Schedule $schedule */
                $schedule = $this->app->make(Schedule::class);

                $event = $schedule->command('dispenser:dispense')->withoutOverlapping();
                config('dispenser.schedule', function (Event $event) {
                    $event->everyMinute();
                })($event);

            });

            $this->publishes([
                __DIR__.'/../config/dispenser.php' => $this->app->configPath('dispenser.php'),
            ], 'dispenser:config');

        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.dispenser.dispense', function () {
            return new Console\DispenseCommand;
        });

        $this->app->singleton('command.dispenser.track-table', function ($app) {
            return new Console\TrackTableCommand($app['files'], $app['composer']);
        });

        $this->commands([
            'command.dispenser.dispense',
            'command.dispenser.track-table',
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.dispenser.dispense',
            'command.dispenser.track-table',
        ];
    }
}
