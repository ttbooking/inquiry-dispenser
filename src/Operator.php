<?php

namespace TTBooking\InquiryDispenser;

abstract class Operator implements Contracts\Operator, Contracts\Parameterizable, Contracts\Comparable
{
    use Concerns\Parameterized;

    abstract public function ready($ready);
    abstract public function match($inquiry);
    abstract public function limit($factor, $limit);
}
