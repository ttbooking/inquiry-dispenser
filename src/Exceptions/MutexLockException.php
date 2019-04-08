<?php

namespace TTBooking\InquiryDispenser\Exceptions;

use Exception;
use Illuminate\Console\Scheduling\Event;

class MutexLockException extends Exception
{
    /**
     * Locking event object.
     *
     * @var Event
     */
    protected $event;

    /**
     * Set locking event object.
     *
     * @param Event $event
     * @return $this
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get locking event object.
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
