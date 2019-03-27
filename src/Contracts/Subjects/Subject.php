<?php

namespace TTBooking\InquiryDispenser\Contracts\Subjects;

use Illuminate\Support\Collection;

interface Subject
{
    /**
     * @return Collection|static[]
     */
    public static function all();

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
