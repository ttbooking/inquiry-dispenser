<?php

namespace TTBooking\InquiryDispenser\Contracts;

use Illuminate\Console\Scheduling\Event;

interface Schedule
{
    /**
     * @param Event $checkout
     * @return $this
     */
    public function checkout(Event $checkout);

    /**
     * @param Event $dispense
     * @return $this
     */
    public function dispense(Event $dispense);
}
