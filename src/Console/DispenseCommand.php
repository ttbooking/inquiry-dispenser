<?php

namespace TTBooking\InquiryDispenser\Console;

use TTBooking\InquiryDispenser\Subjects\Match;

class DispenseCommand extends MutexCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dispenser:dispense';

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
        while (!is_null($match = Match::all()->shift())) $match->marry();
    }
}
