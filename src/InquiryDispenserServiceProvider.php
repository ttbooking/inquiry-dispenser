<?php

namespace TTBooking\InquiryDispenser;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use TTBooking\InquiryDispenser\Contracts\Schedule as ScheduleContract;

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
                if ($this->app->bound(ScheduleContract::class)) {
                    $this->app->make(ScheduleContract::class)->checkout($checkout);
                }

                $dispense = $schedule->command('dispenser:dispense')->withoutOverlapping();
                if ($this->app->bound(ScheduleContract::class)) {
                    $this->app->make(ScheduleContract::class)->dispense($dispense);
                }

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
        foreach (config('dispenser.repositories') as $abstract => $concrete) {
            $this->app->singleton($abstract, $concrete);
        }

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
