<?php

namespace Daniser\InquiryDispenser\Concerns;

use Daniser\InquiryDispenser\FactorTrack;
use Daniser\InquiryDispenser\TrackPeriod;

/**
 * @property-read FactorTrack|TrackPeriod[] $track
 */
trait Tracked
{
    use HasEvents;

    /**
     * @return FactorTrack|TrackPeriod[]
     */
    public function track()
    {
        return new FactorTrack($this);
    }

    public function checkout()
    {
        if ($this->active !== $this->track->getLastState()) {
            $this->fireFactorEvent($this->active ? 'activating' : 'deactivating', false);
            $this->track->snapshot(true);
            $this->fireFactorEvent($this->active ? 'activated' : 'deactivated', false);
        }
    }

    protected function periodActive()
    {
        return $this->track->getSecondsActive();
    }
}
