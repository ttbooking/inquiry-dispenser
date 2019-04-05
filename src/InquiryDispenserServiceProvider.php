<?php

namespace TTBooking\InquiryDispenser;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use TTBooking\InquiryDispenser\Contracts\Repositories\InquiryRepository;
use TTBooking\InquiryDispenser\Contracts\Repositories\OperatorRepository;
use TTBooking\InquiryDispenser\Contracts\Repositories\MatchRepository;
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
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach ([
            'dispenser.inquiry.repository' => InquiryRepository::class,
            'dispenser.operator.repository' => OperatorRepository::class,
            'dispenser.match.repository' => MatchRepository::class,
            'dispenser.schedule' => ScheduleContract::class,
        ] as $alias => $abstract) if (!is_null($concrete = config($alias))) {
            $this->app->alias($abstract, $alias);
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

        if ($this->app->runningInConsole()) {

            $this->app->resolving(Schedule::class, function (Schedule $schedule) {
                if ($this->app->bound('dispenser.schedule')) {
                    $this->app->make('dispenser.schedule')
                        ->checkout($schedule->command('dispenser:checkout')->withoutOverlapping())
                        ->dispense($schedule->command('dispenser:dispense')->withoutOverlapping());
                }
            });

            $this->publishes([
                __DIR__.'/../config/dispenser.php' => $this->app->configPath('dispenser.php'),
            ], 'dispenser:config');

        }
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
            'dispenser.inquiry.repository',
            'dispenser.operator.repository',
            'dispenser.match.repository',
            'dispenser.schedule',
            Schedule::class,
        ];
    }
}
