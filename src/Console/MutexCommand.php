<?php

namespace TTBooking\InquiryDispenser\Console;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Contracts\Container\Container;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Mutex;
use Illuminate\Console\Scheduling\CacheMutex;
use TTBooking\InquiryDispenser\Support\MutexEvent;
use TTBooking\InquiryDispenser\Exceptions\MutexLockException;

abstract class MutexCommand extends Command
{
    /**
     * Force exclusive lock on console command.
     *
     * @var bool
     */
    protected $forceExclusive = false;

    /**
     * Default console command lock expiration timeout in minutes.
     *
     * @var int
     */
    protected $defaultLockTimeout = 1440;

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (!$this->forceExclusive) {
            $this->addOption('exclusive', 'x', InputOption::VALUE_OPTIONAL,
                'Run command exclusively (with optional expiration time in minutes)');
        }
    }

    /**
     * Check exclusive lock on console command.
     *
     * @return bool
     */
    public function isExclusive()
    {
        return $this->forceExclusive || $this->input->hasParameterOption(['--exclusive', '-x']);
    }

    /**
     * Get console command lock expiration timeout in minutes.
     *
     * @return int
     */
    public function getLockTimeout()
    {
        if ($this->forceExclusive) return $this->defaultLockTimeout;
        return $this->option('exclusive') ?: $this->defaultLockTimeout;
    }

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @return void
     * @throws MutexLockException
     */
    public function handle(Container $container)
    {
        $mutex = $container->bound(Mutex::class)
            ? $container->make(Mutex::class)
            : $container->make(CacheMutex::class);

        $event = new MutexEvent($mutex, $this->getName());
        $event->withoutOverlapping($this->getLockTimeout());
        if ($this->isExclusive() ? !$mutex->create($event) : $mutex->exists($event)) {
            //throw (new MutexLockException('Command already running'))->setEvent($event);
            return;
        }

        $this->laravel->call([$this, 'handleExclusive']);

        if ($this->isExclusive()) {
            $event->callAfterCallbacks($container);
        }
    }
}
