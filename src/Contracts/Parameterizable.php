<?php

namespace TTBooking\InquiryDispenser\Contracts;

use Illuminate\Support\Collection;

interface Parameterizable
{
    /**
     * Test parameterizable object factor activity.
     *
     * @param string|string[] ...$factors
     * @return bool
     */
    function is(...$factors);

    /**
     * Get parameterizable object trait(s).
     *
     * @param string[]|string ...$traits
     * @return Collection|mixed[]|mixed
     */
    function get(...$traits);
}
