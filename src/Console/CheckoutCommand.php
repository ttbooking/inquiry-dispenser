<?php

namespace TTBooking\InquiryDispenser\Console;

use Illuminate\Console\Command;
use TTBooking\InquiryDispenser\Subjects\Inquiry;
use TTBooking\InquiryDispenser\Subjects\Operator;
use TTBooking\InquiryDispenser\Subjects\Match;

class CheckoutCommand extends Command
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
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Inquiry::all() as $inquiry) $inquiry->checkout();
        foreach (Operator::all() as $operator) $operator->checkout();
        foreach (Match::all() as $match) $match->checkout();
    }
}
