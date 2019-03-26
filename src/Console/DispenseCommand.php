<?php

namespace TTBooking\InquiryDispenser\Console;

use Illuminate\Console\Command;
use TTBooking\InquiryDispenser\Contracts\Repositories\InquiryRepository;
use TTBooking\InquiryDispenser\Subjects\Match;

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
     * @param InquiryRepository $inquiryRepository
     * @return void
     */
    public function handle(InquiryRepository $inquiryRepository)
    {
        $inquiryIds = collect($this->argument('inquiry'));

        $inquiries = $inquiryIds->isEmpty()
            ? $inquiryRepository->all()
            : $inquiryIds->map(function ($inquiryId) use ($inquiryRepository) {
                return $inquiryRepository->get($inquiryId);
            });

        foreach (Match::from($inquiries) as $match) {
            $match->marry();
        }
    }
}
