<?php

namespace TTBooking\InquiryDispenser\Console;

use TTBooking\InquiryDispenser\Subjects\Inquiry;
use TTBooking\InquiryDispenser\Subjects\Operator;
use TTBooking\InquiryDispenser\Subjects\Match;

class CheckoutCommand extends MutexCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dispenser:checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check out subject state change';

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
        foreach (Inquiry::all() as $inquiry) $inquiry->checkout();
        foreach (Operator::all() as $operator) $operator->checkout();
        foreach (Match::all() as $match) $match->checkout();
    }
}
