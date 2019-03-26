<?php

namespace TTBooking\InquiryDispenser\Contracts\Factors;

use ArrayAccess;

interface Factor extends ArrayAccess
{
    /**
     * @return bool
     */
    public function active();

    /**
     * @return void
     */
    public function checkout();
}
