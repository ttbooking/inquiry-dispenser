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
        Factors\Factor::setEventDispatcher($this->app['events']);

        if ($this->app->runningInConsole()) {

            $this->app->booted(function () {

                /** @var Schedule $schedule */
                $schedule = $this->app->make(Schedule::class);

                $checkout = $schedule->command('dispenser:checkout')->withoutOverlapping();
                config('dispenser.schedule.checkout', function (Event $checkout) {
                    $checkout->everyMinute();
                })($checkout);

                $dispense = $schedule->command('dispenser:dispense')->withoutOverlapping();
                config('dispenser.schedule.dispense', function (Event $dispense) {
                    $dispense->everyMinute();
                })($dispense);

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
        $this->app->singleton('command.dispenser.checkout', function () {
            return new Console\CheckoutCommand;
        });

        $this->app->singleton('command.dispenser.dispense', function () {
            return new Console\DispenseCommand;
        });

        $this->commands([
            'command.dispenser.checkout',
            'command.dispenser.dispense',
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
            'command.dispenser.checkout',
            'command.dispenser.dispense',
        ];
    }
}
