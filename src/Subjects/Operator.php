<?php

namespace TTBooking\InquiryDispenser\Subjects;

use TTBooking\InquiryDispenser\Contracts\Subjects\Operator as OperatorContract;

abstract class Operator extends Subject implements OperatorContract
{
    abstract public function ready($ready);
    abstract public function match($inquiry);
    abstract public function limit($factor, $limit);
}
