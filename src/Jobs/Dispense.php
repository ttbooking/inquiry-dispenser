<?php

namespace TTBooking\InquiryDispenser\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use TTBooking\InquiryDispenser\Exceptions\DispenseException;
use TTBooking\InquiryDispenser\Subjects\IOMatch;

class Dispense implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            while (!is_null($match = IOMatch::all(true)->shift())) $match->marry();
        } catch (\Throwable $e) {
            throw new DispenseException('Dispense operation failed.', $e->getCode(), $e);
        }
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [(new WithoutOverlapping)->dontRelease()->expireAfter(180)];
    }
}
