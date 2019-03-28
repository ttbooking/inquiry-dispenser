<?php

namespace TTBooking\InquiryDispenser\Console;

use Illuminate\Console\Command;
use TTBooking\InquiryDispenser\Subjects\Match;

class DispenseCommand extends Command
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
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        while (!is_null($match = Match::all()->shift())) $match->marry();
    }
}
