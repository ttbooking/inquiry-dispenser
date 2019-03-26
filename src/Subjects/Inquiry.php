<?php

namespace TTBooking\InquiryDispenser\Subjects;

use TTBooking\InquiryDispenser\Contracts\Subjects\Inquiry as InquiryContract;
use Serializable;

abstract class Inquiry extends Subject implements InquiryContract, Serializable
{
    public function serialize()
    {
        return (string)$this->getId();
    }

    abstract public function bind($operator);
    abstract public function unbind();
    abstract public function bound();
    abstract public function match($inquiry);
}
