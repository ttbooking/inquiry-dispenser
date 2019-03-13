<?php

namespace TTBooking\InquiryDispenser\Contracts;

interface Comparable
{
    /**
     * @param static $other
     * @return int
     */
    function compareTo($other);
}
