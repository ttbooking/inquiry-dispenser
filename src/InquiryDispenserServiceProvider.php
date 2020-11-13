<?php

namespace TTBooking\InquiryDispenser;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule as ConsoleSchedule;
use TTBooking\InquiryDispenser\Contracts\Repositories\InquiryRepository;
use TTBooking\InquiryDispenser\Contracts\Repositories\OperatorRepository;
use TTBooking\InquiryDispenser\Contracts\Subjects\IOMatch;
use TTBooking\InquiryDispenser\Contracts\Schedule;

class InquiryDispenserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = true;

    /**
     * Implementations provided by the service provider.
     *
     * @var array
     */
    protected $provides = [
        'dispenser.repository.inquiry'  => InquiryRepository::class,
        'dispenser.repository.operator' => OperatorRepository::class,
        'dispenser.match'               => [IOMatch::class, false],
        'dispenser.schedule'            => Schedule::class,
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
        foreach ($this->provides as $alias => $def) {
            list($abstract, $shared) = array_pad((array) $def, 2, true);
            if (!is_null($concrete = config($alias))) {
                $this->app->alias($abstract, $alias);
                $this->app->bind($abstract, $concrete, $shared);
            }
        }

        foreach ($this->commands as $alias => $command) {
            $this->app->singleton($alias, $command);
        }

        $this->commands(array_keys($this->commands));

        if ($this->app->runningInConsole()) {

            $this->app->resolving(ConsoleSchedule::class, function (ConsoleSchedule $schedule) {
                if ($this->app->bound('dispenser.schedule')) {
                    $dispenserSchedule = $this->app->make('dispenser.schedule');
                    foreach (get_class_methods($dispenserSchedule) as $command) {
                        $dispenserSchedule->$command(
                            $schedule->command('dispenser:'.Str::kebab($command))
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

            [ConsoleSchedule::class]

        );
    }
}
