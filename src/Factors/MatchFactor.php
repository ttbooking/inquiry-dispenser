<?php

namespace TTBooking\InquiryDispenser\Factors;

use TTBooking\InquiryDispenser\Contracts\Subjects\IOMatch;

/**
 * @property-read IOMatch $match
 */
abstract class MatchFactor extends Factor
{
    /** @var IOMatch $subject */
    protected $subject;

    /**
     * @return IOMatch
     */
    public function match()
    {
        return $this->subject;
    }
}
