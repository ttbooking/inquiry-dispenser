<?php

namespace TTBooking\InquiryDispenser;

/**
 * @property-read Contracts\Match $match
 */
abstract class MatchFactor extends Factor
{
    /** @var Contracts\Match $subject */
    protected $subject;

    /**
     * @return Contracts\Match
     */
    public function match()
    {
        return $this->subject;
    }
}
