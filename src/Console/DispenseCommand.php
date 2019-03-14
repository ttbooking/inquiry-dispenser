<?php

namespace TTBooking\InquiryDispenser\Console;

use Illuminate\Console\Command;
//use TTBooking\InquiryDispenser\MatchFactory;
use TTBooking\InquiryDispenser\Match;
use TTBooking\InquiryDispenser\Inquiry;

class DispenseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispenser:dispense
        {inquiry* : Space-separated list of inquiry identifiers to dispense}';

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
        /*$matches = $this->laravel->make(MatchFactory::class);
        foreach ($matches->applicable() as $match) {
            $match->marry();
        }*/
        $inquiries = array_map(function ($inquiryId) {
            return Inquiry::fromId($inquiryId);
        }, $this->argument('inquiry'));
        foreach (Match::from($inquiries) as $match) {
            $match->marry();
        }
    }
}
