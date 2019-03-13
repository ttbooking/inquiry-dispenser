<?php

namespace TTBooking\InquiryDispenser\Observers;

use TTBooking\InquiryDispenser\Contracts\TrackedFactor;

class TrackFactorState
{
    /**
     * @param TrackedFactor $factor
     */
    public function activated($factor)
    {
        if ($factor instanceof TrackedFactor) {
            $factor->track()->snapshot(true);
        }
    }

    /**
     * @param TrackedFactor $factor
     */
    public function deactivated($factor)
    {
        if ($factor instanceof TrackedFactor) {
            $factor->track()->snapshot(true);
        }
    }
}
