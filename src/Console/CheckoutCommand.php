<?php

namespace TTBooking\InquiryDispenser\Console;

use Illuminate\Console\Command;

class CheckoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispenser:checkout
        {inquiry* : Space-separated list of inquiry identifiers to checkout}';

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
        
    }
}
