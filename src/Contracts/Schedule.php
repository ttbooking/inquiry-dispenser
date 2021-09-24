<?php

namespace TTBooking\InquiryDispenser\Contracts;

use Illuminate\Console\Scheduling\Schedule as ConsoleSchedule;

interface Schedule
{
    /**
     * @param ConsoleSchedule $schedule
     * @return void
     */
    public function checkout(ConsoleSchedule $schedule);

    /**
     * @param ConsoleSchedule $schedule
     * @return void
     */
    public function dispense(ConsoleSchedule $schedule);
}
