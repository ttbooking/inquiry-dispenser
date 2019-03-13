<?php

namespace TTBooking\InquiryDispenser\Contracts;

use DateTimeInterface as DateTime;

use Illuminate\Support\Collection;

interface TrackedParameterizable extends Parameterizable
{
    /**
     * Test parameterizable object factor activity for specific timestamp.
     *
     * @param string|string[] $factors
     * @param DateTime|null $queryTime
     * @return bool
     */
    function was($factors, DateTime $queryTime = null);

    /**
     * Get parameterizable object trait(s) for specific timestamp.
     *
     * @param string[]|string $traits
     * @param DateTime|null $queryTime
     * @return Collection|mixed[]|mixed
     */
    function getAsOf($traits, DateTime $queryTime = null);
}
