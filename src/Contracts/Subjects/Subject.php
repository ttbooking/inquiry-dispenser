<?php

namespace TTBooking\InquiryDispenser\Contracts\Subjects;

use Illuminate\Support\Collection;

interface Subject
{
    /**
     * @param bool $forDispense
     * @return Collection|static[]
     */
    public static function all($forDispense = false);

    /**
     * Test subject factor activity.
     *
     * @param string|string[] ...$factors
     * @return bool
     */
    public function is(...$factors);

    /**
     * Get subject trait(s).
     *
     * @param string[]|string ...$traits
     * @return Collection|mixed[]|mixed
     */
    public function get(...$traits);
}
