<?php

namespace TTBooking\InquiryDispenser\Support;

use Illuminate\Console\Scheduling\Event;

class MutexEvent extends Event
{
    /**
     * Get the mutex name for the scheduled command.
     *
     * @return string
     */
    public function mutexName()
    {
        return 'framework'.DIRECTORY_SEPARATOR.'command-'.sha1($this->command);
    }
}
