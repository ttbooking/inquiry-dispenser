<?php

namespace TTBooking\InquiryDispenser\Contracts;

use Serializable;

interface TrackedFactor extends Factor, Serializable
{
    /**
     * @return FactorTrack
     */
    function track();
}
