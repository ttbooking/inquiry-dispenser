<?php

namespace TTBooking\InquiryDispenser\Factors;

use TTBooking\InquiryDispenser\Contracts\Subjects\Match;

/**
 * @property-read Match $match
 */
abstract class MatchFactor extends Factor
{
    /** @var Match $subject */
    protected $subject;

    /**
     * @return Match
     */
    public function match()
    {
        return $this->subject;
    }
}
