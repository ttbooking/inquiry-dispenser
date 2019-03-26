<?php

namespace TTBooking\InquiryDispenser\Factors;

use TTBooking\InquiryDispenser\Contracts\Subjects\Inquiry;

/**
 * @property-read Inquiry $inquiry
 */
abstract class InquiryFactor extends Factor
{
    /** @var Inquiry $subject */
    protected $subject;

    public function serialize()
    {
        return (string)$this->inquiry->getId();
    }

    public function unserialize($serialized)
    {
        $this->inquiry = unserialize($serialized);
    }

    /**
     * @return Inquiry
     */
    public function inquiry()
    {
        return $this->subject;
    }

    /**
     * @return int
     */
    protected function periodActive()
    {
        return $this->getQueryTime()->getTimestamp() - $this->inquiry->getDateTime()->getTimestamp();
    }

    /**
     * @return int
     */
    protected function weightIncrement()
    {
        return 0;
    }

    /**
     * @return int
     */
    final public function currentWeightIncrement()
    {
        return $this->periodActive() * $this->weightIncrement();
    }
}
