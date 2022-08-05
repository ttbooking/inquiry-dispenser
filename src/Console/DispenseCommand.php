<?php

namespace TTBooking\InquiryDispenser\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\InquiryDispenser\Subjects\IOMatch;

#[AsCommand(name: 'dispenser:dispense')]
class DispenseCommand extends MutexCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dispenser:dispense';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'dispenser:dispense';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispense incoming user inquiries across operators';

    /**
     * Force exclusive lock on console command.
     *
     * @var bool
     */
    protected $forceExclusive = true;

    /**
     * Default console command lock expiration timeout in minutes.
     *
     * @var int
     */
    protected $defaultLockTimeout = 5;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handleExclusive()
    {
        while (!is_null($match = IOMatch::all(true)->shift())) $match->marry();
    }
}
