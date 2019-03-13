<?php

namespace TTBooking\InquiryDispenser;

use TTBooking\InquiryDispenser\Contracts\Inquiry as InquiryContract;
use TTBooking\InquiryDispenser\Contracts\TrackedParameterizable;
use TTBooking\InquiryDispenser\Contracts\Comparable;
use Serializable;

abstract class Inquiry implements InquiryContract, TrackedParameterizable, Comparable, Serializable
{
    use Concerns\Parameterized;

    public function serialize()
    {
        return (string)$this->getId();
    }

    abstract public function bind($operator);
    abstract public function unbind();
    abstract public function bound();
    abstract public function match($inquiry);
}
