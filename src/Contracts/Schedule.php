<?php

namespace TTBooking\InquiryDispenser\Contracts;

use Illuminate\Console\Scheduling\Event;

interface Schedule
{
    /**
     * @param Event $checkout
     * @return void
     */
    public function checkout(Event $checkout);

    /**
     * @param Event $dispense
     * @return void
     */
    public function dispense(Event $dispense);
}
