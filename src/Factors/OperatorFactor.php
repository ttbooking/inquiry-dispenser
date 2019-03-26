<?php

namespace TTBooking\InquiryDispenser\Factors;

use TTBooking\InquiryDispenser\Contracts\Subjects\Operator;

/**
 * @property-read Operator $operator
 */
abstract class OperatorFactor extends Factor
{
    /** @var Operator $subject */
    protected $subject;

    /**
     * @return Operator
     */
    public function operator()
    {
        return $this->subject;
    }
}
