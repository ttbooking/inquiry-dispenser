<?php

namespace TTBooking\InquiryDispenser\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Inquiry;
use TTBooking\InquiryDispenser\Contracts\Subjects\Operator;
use TTBooking\InquiryDispenser\Contracts\Subjects\Match;
use TTBooking\InquiryDispenser\Contracts\Repositories\InquiryRepository;
use TTBooking\InquiryDispenser\Contracts\Repositories\OperatorRepository;
use TTBooking\InquiryDispenser\Contracts\Repositories\MatchRepository;

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
        /** @var Collection|Inquiry[] $inquiries */
        $inquiries = $this->laravel->make(InquiryRepository::class)->all();
        foreach ($inquiries as $inquiry) $inquiry->checkout();

        /** @var Collection|Operator[] $operators */
        $operators = $this->laravel->make(OperatorRepository::class)->all();
        foreach ($operators as $operator) $operator->checkout();

        /** @var Collection|Match[] $matches */
        $matches = $this->laravel->make(MatchRepository::class)->all();
        foreach ($matches as $match) $match->checkout();
    }
}
