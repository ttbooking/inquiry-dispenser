<?php

namespace Daniser\InquiryDispenser\Concerns;

use Daniser\InquiryDispenser\FactorTrack;
use Daniser\InquiryDispenser\TrackPeriod;

/**
 * @property-read FactorTrack|TrackPeriod[] $track
 */
trait Tracked
{
    /**
     * @return FactorTrack|TrackPeriod[]
     */
    public function track()
    {
        return new FactorTrack($this);
    }

    protected function periodActive()
    {
        return $this->track->getSecondsActive();
    }
}
