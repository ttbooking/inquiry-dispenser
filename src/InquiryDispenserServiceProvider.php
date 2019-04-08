<?php

namespace TTBooking\InquiryDispenser;

use Illuminate\Support\Str;
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
     * Implementations provided by the service provider.
     *
     * @var array
     */
    protected $provides = [
        'dispenser.inquiry.repository'  => InquiryRepository::class,
        'dispenser.operator.repository' => OperatorRepository::class,
        'dispenser.match.repository'    => MatchRepository::class,
        'dispenser.schedule'            => ScheduleContract::class,
    ];

    /**
     * Console commands provided by the service provider.
     *
     * @var array
     */
    protected $commands = [
        'command.dispenser.checkout'    => Console\CheckoutCommand::class,
        'command.dispenser.dispense'    => Console\DispenseCommand::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->provides as $alias => $abstract) {
            if (!is_null($concrete = config($alias))) {
                $this->app->alias($abstract, $alias);
                $this->app->singleton($abstract, $concrete);
            }
        }

        foreach ($this->commands as $alias => $command) {
            $this->app->singleton($alias, $command);
        }

        $this->commands(array_keys($this->commands));

        if ($this->app->runningInConsole()) {

            $this->app->resolving(Schedule::class, function (Schedule $schedule) {
                if ($this->app->bound('dispenser.schedule')) {
                    $dispenserSchedule = $this->app->make('dispenser.schedule');
                    foreach (get_class_methods($dispenserSchedule) as $command) {
                        $dispenserSchedule->$command(
                            $schedule->command('dispenser:'.Str::kebab($command))->withoutOverlapping()
                        );
                    }
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
        return array_merge(

            array_filter(array_keys($this->provides), function ($alias) {
                return !is_null(config($alias));
            }),

            array_keys($this->commands),

            [Schedule::class]

        );
    }
}
